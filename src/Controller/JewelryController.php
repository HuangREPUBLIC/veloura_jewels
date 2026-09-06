<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Response;
use Cake\Routing\Router;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Log\Log;
use Cake\Mailer\Mailer;
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
            'quickAdd',
            'cartDrawer',
            'removeFromCart',
            'createCheckoutSession',
            'success',
            'cancel',
            'resumeCheckout',
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

    public function index(?string $categorySlug = null)
    {
        $productType = 'jewelry';

        $categoriesTable = $this->fetchTable('Categories');
        $categories = $categoriesTable->find()
            ->where(['type' => $productType])
            ->orderBy(['name' => 'ASC'])
            ->all();

        // Resolve slug to category ID
        $categoryId = (int)$this->request->getQuery('category');
        if ($categorySlug !== null && $categoryId === 0) {
            $cat = $categoriesTable->find()
                ->where(['type' => $productType, 'LOWER(name)' => strtolower(str_replace('-', ' ', $categorySlug))])
                ->first();
            if ($cat) {
                $categoryId = $cat->id;
            }
        }

        $minPrice   = $this->request->getQuery('min_price');
        $maxPrice   = $this->request->getQuery('max_price');
        $sortBy     = $this->request->getQuery('sort') ?? 'newest';

        $wishlistIds = $this->getWishlistIds();

        if ($sortBy === 'bestsales') {
            $products = $this->Products
                ->find('bestSales', productType: $productType, limit: 4)
                ->contain(['ProductImages', 'ProductVariants'])
                ->all();
            foreach ($products as $product) {
                $product->is_bestsales = true;
            }
            $pageContent = $this->fetchTable('PageContents')->getForPage($productType);
            $this->set(compact('products', 'categories', 'categoryId', 'minPrice', 'maxPrice', 'sortBy', 'pageContent', 'wishlistIds'));
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
            ->contain(['ProductImages', 'ProductVariants'])
            ->orderBy($sortOptions[$sortBy] ?? $sortOptions['newest']);

        if ($categoryId > 0) {
            $query->innerJoinWith('Category', function ($q) use ($categoryId, $productType) {
                return $q->where(['Category.id' => $categoryId, 'Category.type' => $productType]);
            });
        } else {
            $query->innerJoinWith('Category', function ($q) use ($productType) {
                return $q->where(['Category.type' => $productType]);
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

        $pageContent = $this->fetchTable('PageContents')->getForPage($productType);
        $this->set(compact('products', 'categories', 'categoryId', 'minPrice', 'maxPrice', 'sortBy', 'pageContent', 'wishlistIds'));
    }

    public function homeDecor(?string $categorySlug = null)
    {
        $productType = 'home_decor';

        $categoriesTable = $this->fetchTable('Categories');
        $categories = $categoriesTable->find()
            ->where(['type' => $productType])
            ->orderBy(['name' => 'ASC'])
            ->all();

        // Resolve slug to category ID
        $categoryId = (int)$this->request->getQuery('category');
        if ($categorySlug !== null && $categoryId === 0) {
            $cat = $categoriesTable->find()
                ->where(['type' => $productType, 'LOWER(name)' => strtolower(str_replace('-', ' ', $categorySlug))])
                ->first();
            if ($cat) {
                $categoryId = $cat->id;
            }
        }


        $minPrice   = $this->request->getQuery('min_price');
        $maxPrice   = $this->request->getQuery('max_price');
        $sortBy     = $this->request->getQuery('sort') ?? 'newest';

        $wishlistIds = $this->getWishlistIds();

        if ($sortBy === 'bestsales') {
            $products = $this->Products
                ->find('bestSales', productType: $productType, limit: 4)
                ->contain(['ProductImages', 'ProductVariants'])
                ->all();
            foreach ($products as $product) {
                $product->is_bestsales = true;
            }
            $pageContent = $this->fetchTable('PageContents')->getForPage($productType);
            $this->set(compact('products', 'categories', 'categoryId', 'minPrice', 'maxPrice', 'sortBy', 'pageContent', 'wishlistIds'));
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
            ->contain(['ProductImages', 'ProductVariants'])
            ->orderBy($sortOptions[$sortBy] ?? $sortOptions['newest']);

        if ($categoryId > 0) {
            $query->innerJoinWith('Category', function ($q) use ($categoryId, $productType) {
                return $q->where(['Category.id' => $categoryId, 'Category.type' => $productType]);
            });
        } else {
            $query->innerJoinWith('Category', function ($q) use ($productType) {
                return $q->where(['Category.type' => $productType]);
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

        $pageContent = $this->fetchTable('PageContents')->getForPage($productType);
        $this->set(compact('products', 'categories', 'categoryId', 'minPrice', 'maxPrice', 'sortBy', 'pageContent', 'wishlistIds'));
    }

    public function view($id = null)
    {
        $product = $this->Products->get($id, contain: ['ProductImages', 'ProductVariants', 'Category']);
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
            if ($this->request->is('ajax')) {
                throw new BadRequestException('Invalid size selection.');
            }
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

        if ($this->request->is('ajax')) {
            return $this->cartDrawerResponse();
        }

        $this->Flash->success('Product added to cart.');
        return $this->redirect(['action' => 'view', $productId]);
    }

    /**
     * Adds a listing card's product straight to the cart. Only products with a
     * single in-stock variant go in unattended; anything with a real size
     * choice comes back as a quick view so the customer picks one.
     */
    public function quickAdd()
    {
        $this->request->allowMethod(['post']);

        $productId = (int)$this->request->getData('product_id');
        $product   = $this->Products->get($productId, contain: ['ProductImages', 'ProductVariants']);

        $available = array_values(array_filter(
            $product->product_variants,
            fn($variant) => $variant->stock > 0
        ));

        if (empty($available)) {
            return $this->jsonResponse(['redirect' => Router::url(['action' => 'view', $productId])]);
        }

        if (count($available) > 1) {
            return $this->jsonResponse([
                'quickView' => $this->createView()->element('quick_view', ['product' => $product]),
            ]);
        }

        $variant = $available[0];
        $session = $this->request->getSession();
        $cart    = $session->read('Cart') ?? [];
        $key     = $productId . '_' . $variant->id;
        $current = (int)($cart[$key]['quantity'] ?? 0);

        if ($current < $variant->stock) {
            $cart[$key] = [
                'product_id' => $productId,
                'variant_id' => $variant->id,
                'quantity'   => $current + 1,
            ];
            $session->write('Cart', $cart);
        }

        return $this->cartDrawerResponse();
    }

    /**
     * Cart quantity updates. The cart lives in the drawer only, so a plain GET
     * has nothing to render and goes back to the listing.
     */
    public function cart()
    {
        if ($this->request->is('post')) {
            $session = $this->request->getSession();
            $cart    = $session->read('Cart') ?? [];
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

            if ($this->request->is('ajax')) {
                return $this->cartDrawerResponse();
            }
        }

        return $this->redirect(['action' => 'index', '?' => ['cart' => 'open']]);
    }

    /**
     * Contents of the slide-out cart, fetched by webroot/js/cart-drawer.js.
     */
    public function cartDrawer()
    {
        $this->request->allowMethod(['get']);

        return $this->cartDrawerResponse();
    }

    public function removeFromCart()
    {
        $this->request->allowMethod(['post']);

        $session = $this->request->getSession();
        $cart    = $session->read('Cart') ?? [];
        $key     = $this->request->getData('cart_key');

        unset($cart[$key]);
        $session->write('Cart', $cart);

        if ($this->request->is('ajax')) {
            return $this->cartDrawerResponse();
        }

        return $this->redirect(['action' => 'index', '?' => ['cart' => 'open']]);
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

        $baseUrl = $this->request->scheme() . '://' . $this->request->host() . $this->request->getAttribute('base');

        $sessionParams = [
            'ui_mode' => 'embedded_page',
            'mode' => 'payment',
            'line_items' => $lineItems,
            'return_url' => $baseUrl . '/checkout/success?session_id={CHECKOUT_SESSION_ID}',
            'shipping_address_collection' => [
                'allowed_countries' => ['AU', 'NZ', 'US', 'GB', 'CA', 'SG', 'HK', 'JP'],
            ],
            'billing_address_collection' => 'required',
            'phone_number_collection' => ['enabled' => true],
            'metadata' => ['order_id' => (string)$order->id],
        ];

        if ($customerEmail) {
            $sessionParams['customer_email'] = $customerEmail;
        }

        $session = $stripe->checkout->sessions->create($sessionParams);

        $order->stripe_session_id = $session->id;
        $this->Orders->saveOrFail($order);

        $clientSecret = $session->client_secret;
        $sessionId = $session->id;
        $publishableKey = Configure::read('Stripe.publishableKey');
        $this->set(compact('clientSecret', 'publishableKey', 'products', 'total', 'sessionId'));
        $this->viewBuilder()->disableAutoLayout();
        $this->render('payment');
    }

    public function success()
    {
        $sessionId = (string)$this->request->getQuery('session_id');
        $order = null;

        if ($sessionId !== '') {
            $order = $this->Orders->find()
                ->contain(['OrderItems' => ['Products' => ['ProductImages']]])
                ->where(['stripe_session_id' => $sessionId])
                ->first();
        }

        $this->request->getSession()->delete('Cart');
        $this->set(compact('order'));
    }

    public function cancel()
    {
        $cancelWindowMinutes = 30;
        $sessionId = (string)($this->request->getQuery('session_id') ?? $this->request->getData('session_id') ?? '');
        $order = null;

        if ($sessionId !== '') {
            $identity = $this->request->getAttribute('identity');
            $conditions = ['stripe_session_id' => $sessionId];
            if ($identity) {
                $conditions['user_id'] = $identity->get('id');
            }
            $order = $this->Orders->find()
                ->contain(['OrderItems'])
                ->where($conditions)
                ->first();
        }

        // Auto-cancel pending orders whose window has expired
        if ($order && $order->status === 'pending') {
            $windowEnd = $order->created->modify("+{$cancelWindowMinutes} minutes");
            if ((new \DateTime()) > $windowEnd) {
                $order->status = 'cancelled';
                $this->Orders->save($order);
            }
        }

        if ($this->request->is('post') && $order && $order->status === 'pending') {
            $windowEnd = $order->created->modify("+{$cancelWindowMinutes} minutes");
            if ((new \DateTime()) > $windowEnd) {
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

        $this->set(compact('order', 'cancelWindowMinutes'));
    }

    public function resumeCheckout()
    {
        $sessionId = (string)$this->request->getQuery('session_id');

        if (!$sessionId) {
            return $this->redirect(['action' => 'index', '?' => ['cart' => 'open']]);
        }

        $order = $this->Orders->find()
            ->contain(['OrderItems'])
            ->where(['stripe_session_id' => $sessionId, 'status' => 'pending'])
            ->first();

        if (!$order) {
            return $this->redirect(['action' => 'cancel', '?' => ['session_id' => $sessionId]]);
        }

        $stripe = new StripeClient(Configure::read('Stripe.secretKey'));
        try {
            $session = $stripe->checkout->sessions->retrieve($sessionId);
        } catch (\Exception $e) {
            return $this->redirect(['action' => 'index', '?' => ['cart' => 'open']]);
        }

        if ($session->status !== 'open') {
            return $this->redirect(['action' => 'cancel', '?' => ['session_id' => $sessionId]]);
        }

        $variantsTable = $this->fetchTable('ProductVariants');
        $products = [];
        $total = 0;

        foreach ($order->order_items as $item) {
            try {
                $product = $this->Products->get($item->product_id, contain: ['ProductImages']);
                $variant = $variantsTable->get($item->variant_id);
            } catch (RecordNotFoundException $e) {
                continue;
            }
            $product->variant  = $variant;
            $product->quantity = $item->quantity;
            $product->subtotal = (float)$item->subtotal;
            $total += $product->subtotal;
            $products[] = $product;
        }

        $clientSecret   = $session->client_secret;
        $publishableKey = Configure::read('Stripe.publishableKey');

        $this->set(compact('clientSecret', 'publishableKey', 'products', 'total', 'sessionId'));
        $this->viewBuilder()->disableAutoLayout();
        $this->render('payment');
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
                        ->contain(['Products.ProductImages'])
                        ->toArray();

                    foreach ($items as $item) {
                        if (!$item->variant_id) continue;
                        $variantsTable = $this->fetchTable('ProductVariants');
                        $variant = $variantsTable->get($item->variant_id);
                        $variant->stock = max(0, $variant->stock - (int)$item->quantity);
                        $variantsTable->saveOrFail($variant);
                    }

                    if (!empty($order->customer_email)) {
                        try {
                            $mailer = new Mailer('default');
                            $mailer->setEmailFormat('both')
                                ->setTo($order->customer_email)
                                ->setSubject('Your Veloura Jewels order #' . $order->id . ' is confirmed');
                            $mailer->viewBuilder()->setTemplate('order_confirmation');
                            $mailer->setViewVars(['order' => $order, 'items' => $items]);
                            $mailer->deliver();
                        } catch (\Exception $e) {
                            Log::error('Order confirmation email failed: ' . $e->getMessage());
                        }
                    }

                }
            }
        }

        http_response_code(200);
        echo 'OK';
    }

    private function getWishlistIds(): array
    {
        $identity = $this->request->getAttribute('identity');
        if (!$identity) {
            return $this->request->getSession()->read('GuestWishlist') ?? [];
        }
        return $this->fetchTable('Wishlists')->find()
            ->where(['user_id' => $identity->get('id')])
            ->all()
            ->extract('product_id')
            ->toList();
    }

    private function jsonResponse(array $payload): Response
    {
        return $this->response
            ->withType('application/json')
            ->withStringBody((string)json_encode($payload));
    }

    /**
     * The cart drawer's contents, rendered server-side so the markup stays in
     * one template whether it arrives with the page or over fetch.
     */
    private function cartDrawerResponse(): Response
    {
        [$products, $total] = $this->buildCartProductsAndTotal();

        return $this->jsonResponse([
            'html'  => $this->createView()->element('cart_drawer_items', [
                'products' => $products,
                'total'    => $total,
            ]),
            'count' => count($products),
            'total' => '$' . number_format((float)$total, 2),
        ]);
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
