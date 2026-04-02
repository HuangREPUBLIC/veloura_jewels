<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

class JewelryController extends AppController
{
    protected $Products;

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // Public access (no authentication required)
        $this->Authentication->addUnauthenticatedActions([
            'index',
            'view',
            'cart',
            'addToCart',
            'removeFromCart',
        ]);
    }

    public function initialize(): void
    {
        parent::initialize();

        // Load Products table (NOT Jewelry)
        $this->Products = $this->fetchTable('Products');
    }

    /**
     * Product listing page
     */
    public function index()
    {
        $products = $this->Products->find()
            ->contain(['ProductImages'])
            ->orderBy(['Products.id' => 'DESC'])
            ->all();

        $this->set(compact('products'));
    }

    /**
     * Product detail page
     */
    public function view($id = null)
    {
        $product = $this->Products->get($id, contain: ['ProductImages']);

        $this->set(compact('product'));
    }

    /**
     * Add product to cart
     */
    public function addToCart()
    {
        $this->request->allowMethod(['post']);

        $productId = (int)$this->request->getData('product_id');
        $quantity = (int)$this->request->getData('quantity');

        if ($quantity < 1) {
            $quantity = 1;
        }

        $session = $this->request->getSession();
        $cart = $session->read('Cart') ?? [];

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        $session->write('Cart', $cart);

        $this->Flash->success('Product added to cart.');

        return $this->redirect(['action' => 'cart']);
    }

    /**
     * Cart page
     */
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

        $products = [];
        $total = 0;

        if (!empty($cart)) {
            $products = $this->Products->find()
                ->where(['Products.id IN' => array_keys($cart)])
                ->contain(['ProductImages'])
                ->all();

            foreach ($products as $product) {
                $qty = $cart[$product->id];
                $product->quantity = $qty;
                $product->subtotal = $qty * $product->sale_price;
                $total += $product->subtotal;
            }
        }

        $this->set(compact('products', 'total'));
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($id = null)
    {
        $session = $this->request->getSession();
        $cart = $session->read('Cart') ?? [];

        unset($cart[$id]);

        $session->write('Cart', $cart);

        return $this->redirect(['action' => 'cart']);
    }

}

