<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Datasource\Exception\RecordNotFoundException;
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
            'homeDecor',
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
        $productType = 'jewelry';

        $categoriesTable = $this->fetchTable('Categories');
        $categories = $categoriesTable->find()
            ->where(['type' => $productType])
            ->orderBy(['name' => 'ASC'])
            ->all();

        $categoryId = (int)$this->request->getQuery('category');
        $minPrice   = $this->request->getQuery('min_price');
        $maxPrice   = $this->request->getQuery('max_price');
        $sortBy     = $this->request->getQuery('sort') ?? 'newest';

        if ($sortBy === 'bestsales') {
            $products = $this->Products
                ->find('bestSales', productType: $productType, limit: 4)
                ->contain(['ProductImages'])
                ->all();
            foreach ($products as $product) {
                $product->is_bestsales = true;
            }
            $this->set(compact('products', 'categories', 'categoryId', 'minPrice', 'maxPrice', 'sortBy'));
            return;
        }

        $bestSalesIds = collection(
            $this->Products->find('bestSales', productType: $productType, limit: 4)->all()
        )->extract('id')->toList();

        $sortOptions = [
            'newest'     => ['Products.id' => 'DESC'],
            'price_asc'  => ['Products.sale_price' => 'ASC'],
            'price_desc' => ['Products.sale_price' => 'DESC'],
            'featured'   => ['Products.id' => 'DESC'],
        ];

        $query = $this->Products->find()
            ->contain(['ProductImages'])
            ->orderBy($sortOptions[$sortBy] ?? $sortOptions['newest']);

        if ($categoryId > 0) {
            $query->matching('Categories', function ($q) use ($categoryId, $productType) {
                return $q->where([
                    'Categories.id' => $categoryId,
                    'Categories.type' => $productType
                ]);
            });
        } else {
            $query->matching('Categories', function ($q) use ($productType) {
                return $q->where(['Categories.type' => $productType]);
            });
        }

        if ($minPrice !== null && $minPrice !== '') {
            $query->where(['Products.sale_price >=' => (float)$minPrice]);
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where(['Products.sale_price <=' => (float)$maxPrice]);
        }

        if ($sortBy === 'featured') {
            $query->where(['Products.featured' => 1]);
        }

        $products = $query->all();

        foreach ($products as $product) {
            $product->is_bestsales = in_array($product->id, $bestSalesIds);
        }

        $this->set(compact('products', 'categories', 'categoryId', 'minPrice', 'maxPrice', 'sortBy'));
    }

    public function homeDecor()
    {
        $productType = 'home_decor';

        $categoriesTable = $this->fetchTable('Categories');
        $categories = $categoriesTable->find()
            ->where(['type' => $productType])
            ->orderBy(['name' => 'ASC'])
            ->all();

        $categoryId = (int)$this->request->getQuery('category');
        $minPrice   = $this->request->getQuery('min_price');
        $maxPrice   = $this->request->getQuery('max_price');
        $sortBy     = $this->request->getQuery('sort') ?? 'newest';

        if ($sortBy === 'bestsales') {
            $products = $this->Products
                ->find('bestSales', productType: $productType, limit: 4)
                ->contain(['ProductImages'])
                ->all();
            foreach ($products as $product) {
                $product->is_bestsales = true;
            }
            $this->set(compact('products', 'categories', 'categoryId', 'minPrice', 'maxPrice', 'sortBy'));
            return;
        }

        $bestSalesIds = collection(
            $this->Products->find('bestSales', productType: $productType, limit: 4)->all()
        )->extract('id')->toList();

        $sortOptions = [
            'newest'     => ['Products.id' => 'DESC'],
            'price_asc'  => ['Products.sale_price' => 'ASC'],
            'price_desc' => ['Products.sale_price' => 'DESC'],
            'featured'   => ['Products.id' => 'DESC'],
        ];

        $query = $this->Products->find()
            ->contain(['ProductImages'])
            ->orderBy($sortOptions[$sortBy] ?? $sortOptions['newest']);

        if ($categoryId > 0) {
            $query->matching('Categories', function ($q) use ($categoryId, $productType) {
                return $q->where([
                    'Categories.id' => $categoryId,
                    'Categories.type' => $productType
                ]);
            });
        } else {
            $query->matching('Categories', function ($q) use ($productType) {
                return $q->where(['Categories.type' => $productType]);
            });
        }

        if ($minPrice !== null && $minPrice !== '') {
            $query->where(['Products.sale_price >=' => (float)$minPrice]);
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where(['Products.sale_price <=' => (float)$maxPrice]);
        }

        if ($sortBy === 'featured') {
            $query->where(['Products.featured' => 1]);
        }

        $products = $query->all();

        foreach ($products as $product) {
            $product->is_bestsales = in_array($product->id, $bestSalesIds);
        }

        $this->set(compact('products', 'categories', 'categoryId', 'minPrice', 'maxPrice', 'sortBy'));
    }

    public function view($id = null)
    {
        $product = $this->Products->get($id, contain: ['ProductImages', 'ProductVariants']);
        $section = str_starts_with($this->request->getPath(), '/home-decor') ? 'home_decor' : 'jewelry';
        $this->set(compact('product', 'section'));
    }

    public function addToCart()
    {
        $this->request->allowMethod(['post']);

        $productId  = (int)$this->request->getData('product_id');
        $variantId  = (int)$this->request->getData('variant_id');
        $quantity   = (int)$this->request->getData('quantity');

        if ($quantity < 1) $quantity = 1;

        $variantsTable = $this->fetchTable('ProductVariants');
        $variant = $variantsTable->get($variantId);

        if ($variant->product_id !== $productId) {
            $this->Flash->error('Invalid size selection.');
            return $this->redirect(['action' => 'view', $productId]);
        }

        if ($quantity > $variant->stock) {
            $quantity = $variant->stock;
        }

        $session = $this->request->getSession();
        $cart = $session->read('Cart') ?? [];

        $key = $productId . '_' . $variantId;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
            if ($cart[$key]['quantity'] > $variant->stock) {
                $cart[$key]['quantity'] = $variant->stock;
            }
        } else {
            $cart[$key] = [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity'   => $quantity,
            ];
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
            $key      = $this->request->getData('cart_key');
            $quantity = (int)$this->request->getData('quantity');

            if (isset($cart[$key])) {
                if ($quantity <= 0) {
                    unset($cart[$key]);
                } else {
                    $variantsTable = $this->fetchTable('ProductVariants');
                    $variant = $variantsTable->get($cart[$key]['variant_id']);
                    $cart[$key]['quantity'] = min($quantity, $variant->stock);
                }
                $session->write('Cart', $cart);
            }

            return $this->redirect(['action' => 'cart']);
        }

        [$products, $total] = $this->buildCartProductsAndTotal();
        $this->set(compact('products', 'total'));
    }

    public function removeFromCart()
    {
        $this->request->allowMethod(['post']);

        $session = $this->request->getSession();
        $cart    = $session->read('Cart') ?? [];
        $key     = $this->request->getData('cart_key');

        unset($cart[$key]);
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
            $item->variant_id    = $product->variant->id;
            $item->product_name = $product->name;
            $item->selected_size = $product->variant->size;
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

        $sessionParams = [
            'mode' => 'payment',
            'line_items' => $lineItems,
            'success_url' => $this->request->scheme() . '://' . $this->request->host() . $this->request->getAttribute('base') . '/jewelry/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $this->request->scheme() . '://' . $this->request->host() . $this->request->getAttribute('base') . '/checkout/cancel?session_id={CHECKOUT_SESSION_ID}',
            'metadata' => ['order_id' => (string)$order->id],
        ];

        if ($customerEmail) {
            $sessionParams['customer_email'] = $customerEmail;
        }

        $session = $stripe->checkout->sessions->create($sessionParams);

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
                ->contain(['OrderItems'])
                ->where(['stripe_session_id' => $sessionId])
                ->first();
        }

        $this->request->getSession()->delete('Cart');
        $this->set(compact('order'));
    }

    public function cancel()
    {
        $sessionId = (string)($this->request->getQuery('session_id') ?? $this->request->getData('session_id') ?? '');
        $order = null;

        if ($sessionId !== '') {
            $identity = $this->request->getAttribute('identity');
            $conditions = ['stripe_session_id' => $sessionId, 'status' => 'pending'];
            if ($identity) {
                $conditions['user_id'] = $identity->get('id');
            }
            $order = $this->Orders->find()
                ->contain(['OrderItems'])
                ->where($conditions)
                ->first();
        }

        if ($this->request->is('post') && $order) {
            $windowEnd = $order->created->modify('+30 minutes');
            if (new \DateTime() > $windowEnd) {
                $this->Flash->error('The cancellation window has expired.');
                return $this->redirect(['action' => 'cancel', '?' => ['session_id' => $sessionId]]);
            }
            $order->status = 'cancelled';
            $this->Orders->saveOrFail($order);
            $this->Flash->success('Your order has been cancelled.');
            $identity = $this->request->getAttribute('identity');
            return $this->redirect($identity
                ? ['controller' => 'Profile', 'action' => 'orders']
                : ['action' => 'index']
            );
        }

        $cancelWindowMinutes = 30;
        $this->set(compact('order', 'cancelWindowMinutes'));
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
                        if (!$item->variant_id) continue;
                        $variantsTable = $this->fetchTable('ProductVariants');
                        $variant = $variantsTable->get($item->variant_id);
                        $variant->stock = max(0, $variant->stock - (int)$item->quantity);
                        $variantsTable->saveOrFail($variant);
                    }

                }
            }
        }

        http_response_code(200);
        echo 'OK';
    }

    private function buildCartProductsAndTotal(): array
    {
        $session  = $this->request->getSession();
        $cart     = $session->read('Cart') ?? [];
        $products = [];
        $total    = 0;

        if (!empty($cart)) {
            $variantsTable = $this->fetchTable('ProductVariants');

            foreach ($cart as $key => $item) {
                try {
                    $product = $this->Products->get($item['product_id'], contain: ['ProductImages']);
                    $variant = $variantsTable->get($item['variant_id']);
                } catch (RecordNotFoundException $e) {
                    unset($cart[$key]);
                    $session->write('Cart', $cart);
                    continue;
                }

                $qty = (int)$item['quantity'];
                if ($qty > $variant->stock) $qty = $variant->stock;
                if ($qty <= 0) continue;

                $product->cart_key  = $key;
                $product->variant   = $variant;
                $product->quantity  = $qty;
                $product->subtotal  = $qty * (float)$product->sale_price;
                $total += $product->subtotal;
                $products[] = $product;
            }
        }

        return [$products, $total];
    }
}
