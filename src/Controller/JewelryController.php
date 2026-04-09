<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Stripe\StripeClient;
use Stripe\Webhook;

class JewelryController extends AppController
{
    protected $Products;
    protected $Orders;
    protected $OrderItems;

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->addUnauthenticatedActions([
            'index',
            'view',
            'cart',
            'addToCart',
            'removeFromCart',
            'checkout',
            'createCheckoutSession',
            'success',
            'cancel',
            'webhook',
        ]);
    }

    public function initialize(): void
    {
        parent::initialize();

        $this->Products = $this->fetchTable('Products');
        $this->Orders = $this->fetchTable('Orders');
        $this->OrderItems = $this->fetchTable('OrderItems');
    }

    public function index()
    {
        $products = $this->Products->find()
            ->contain(['ProductImages'])
            ->orderBy(['Products.id' => 'DESC'])
            ->all();

        $this->set(compact('products'));
    }

    public function view($id = null)
    {
        $product = $this->Products->get($id, contain: ['ProductImages']);
        $this->set(compact('product'));
    }

    public function addToCart()
    {
        $this->request->allowMethod(['post']);

        $productId = (int)$this->request->getData('product_id');
        $quantity = (int)$this->request->getData('quantity');

        if ($quantity < 1) {
            $quantity = 1;
        }

        $product = $this->Products->get($productId);
        if ($quantity > (int)$product->stock) {
            $quantity = (int)$product->stock;
        }

        $session = $this->request->getSession();
        $cart = $session->read('Cart') ?? [];

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
            if ($cart[$productId] > (int)$product->stock) {
                $cart[$productId] = (int)$product->stock;
            }
        } else {
            $cart[$productId] = $quantity;
        }

        $session->write('Cart', $cart);

        $this->Flash->success('Product added to cart.');
        return $this->redirect(['action' => 'cart']);
    }

    public function cart()
    {
        $session = $this->request->getSession();
        $cart = $session->read('Cart') ?? [];

        if ($this->request->is('post')) {
            $productId = (int)$this->request->getData('product_id');
            $quantity = (int)$this->request->getData('quantity');

            if (isset($cart[$productId])) {
                if ($quantity <= 0) {
                    unset($cart[$productId]);
                } else {
                    $product = $this->Products->get($productId);

                    if ($quantity > (int)$product->stock) {
                        $quantity = (int)$product->stock;
                    }

                    $cart[$productId] = $quantity;
                }

                $session->write('Cart', $cart);
            }

            return $this->redirect(['action' => 'cart']);
        }

        [$products, $total] = $this->buildCartProductsAndTotal();
        $this->set(compact('products', 'total'));
    }

    public function removeFromCart($id = null)
    {
        $session = $this->request->getSession();
        $cart = $session->read('Cart') ?? [];

        unset($cart[$id]);
        $session->write('Cart', $cart);

        return $this->redirect(['action' => 'cart']);
    }

    public function checkout()
    {
        [$products, $total] = $this->buildCartProductsAndTotal();

        if (empty($products)) {
            $this->Flash->error('Your cart is empty.');
            return $this->redirect(['action' => 'cart']);
        }

        $publishableKey = Configure::read('Stripe.publishableKey');
        $this->set(compact('products', 'total', 'publishableKey'));
    }

    public function createCheckoutSession()
    {
        $this->request->allowMethod(['post']);

        [$products, $total] = $this->buildCartProductsAndTotal();

        if (empty($products)) {
            throw new BadRequestException('Cart is empty.');
        }

        $stripeSecretKey = Configure::read('Stripe.secretKey');
        $stripe = new StripeClient($stripeSecretKey);

        $identity = $this->request->getAttribute('identity');
        $userId = $identity ? $identity->get('id') : null;
        $customerEmail = $identity ? $identity->get('email') : null;

        $order = $this->Orders->newEmptyEntity();
        $order->user_id = $userId;
        $order->customer_email = $customerEmail;
        $order->status = 'pending';
        $order->total_amount = $total;
        $order->currency = 'aud';
        $this->Orders->saveOrFail($order);

        foreach ($products as $product) {
            $item = $this->OrderItems->newEmptyEntity();
            $item->order_id = $order->id;
            $item->product_id = $product->id;
            $item->product_name = $product->name;
            $item->unit_price = $product->sale_price;
            $item->quantity = $product->quantity;
            $item->subtotal = $product->subtotal;
            $this->OrderItems->saveOrFail($item);
        }

        $lineItems = [];
        foreach ($products as $product) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'aud',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => (int) round(((float)$product->sale_price) * 100),
                ],
                'quantity' => (int)$product->quantity,
            ];
        }

        $session = $stripe->checkout->sessions->create([
            'mode' => 'payment',
            'line_items' => $lineItems,
            'success_url' => $this->request->scheme() . '://' . $this->request->host() . '/veloura_jewels/jewelry/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $this->request->scheme() . '://' . $this->request->host() . '/veloura_jewels/jewelry/cancel',
            'customer_email' => $customerEmail,
            'metadata' => [
                'order_id' => (string)$order->id,
            ],
        ]);

        $order->stripe_session_id = $session->id;
        $this->Orders->saveOrFail($order);

        return $this->redirect($session->url);
    }

    public function success()
    {
        $sessionId = (string)$this->request->getQuery('session_id');
        $order = null;

        if ($sessionId !== '') {
            $order = $this->Orders->find()
                ->where(['stripe_session_id' => $sessionId])
                ->first();

        }
        $this->request->getSession()->delete('Cart');

        $this->set(compact('order'));


    }

    public function cancel()
    {
    }

    public function webhook()
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;

        $payload = (string)$this->request->getBody();
        $sigHeader = (string)$this->request->getHeaderLine('Stripe-Signature');
        $webhookSecret = Configure::read('Stripe.webhookSecret');

        if (!$webhookSecret) {
            http_response_code(400);
            echo 'Webhook secret not configured';
            return;
        }

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\UnexpectedValueException $e) {
            http_response_code(400);
            echo 'Invalid payload';
            return;
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            http_response_code(400);
            echo 'Invalid signature';
            return;
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $orderId = $session->metadata->order_id ?? null;

            if ($orderId) {
                $order = $this->Orders->get((int)$orderId);

                if ($order->status !== 'paid') {
                    $order->status = 'paid';
                    $order->stripe_payment_intent_id = $session->payment_intent ?? null;
                    $order->customer_email = $session->customer_details->email ?? $order->customer_email;
                    $this->Orders->saveOrFail($order);

                    $items = $this->OrderItems->find()
                        ->where(['order_id' => $order->id])
                        ->all();

                    foreach ($items as $item) {
                        $product = $this->Products->get($item->product_id);
                        $newStock = max(0, (int)$product->stock - (int)$item->quantity);
                        $product->stock = $newStock;
                        $this->Products->saveOrFail($product);
                    }
                }
            }
        }

        http_response_code(200);
        echo 'OK';
    }

    private function buildCartProductsAndTotal(): array
    {
        $session = $this->request->getSession();
        $cart = $session->read('Cart') ?? [];

        $products = [];
        $total = 0;

        if (!empty($cart)) {
            $products = $this->Products->find()
                ->where(['Products.id IN' => array_keys($cart)])
                ->contain(['ProductImages'])
                ->all()
                ->toList();

            foreach ($products as $product) {
                $qty = (int)($cart[$product->id] ?? 0);

                if ($qty > (int)$product->stock) {
                    $qty = (int)$product->stock;
                }

                $product->quantity = $qty;
                $product->subtotal = $qty * (float)$product->sale_price;
                $total += $product->subtotal;
            }

            $products = array_filter($products, fn($p) => $p->quantity > 0);
        }

        return [$products, $total];
    }
}
