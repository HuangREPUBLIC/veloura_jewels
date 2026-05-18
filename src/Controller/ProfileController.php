<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

class ProfileController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
    }

    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['wishlist', 'wishlistToggle', 'addWishlistToCart']);
    }

    public function index()
    {
        $userId = $this->Authentication->getIdentity()->get('id');

        $usersTable  = $this->fetchTable('Users');
        $ordersTable = $this->fetchTable('Orders');

        $user = $usersTable->get($userId);

        $recentOrders = $ordersTable->find()
            ->where(['Orders.user_id' => $userId])
            ->contain(['OrderItems'])
            ->orderByDesc('Orders.created')
            ->limit(5)
            ->all();

        $this->set(compact('user', 'recentOrders'));
    }

    public function edit()
    {
        $userId     = $this->Authentication->getIdentity()->get('id');
        $usersTable = $this->fetchTable('Users');
        $user       = $usersTable->get($userId);

        if ($this->request->is(['patch', 'post', 'put'])) {
            // Only allow safe fields — never role or password here
            $allowed = ['first_name', 'last_name', 'phone', 'address'];
            $data    = array_intersect_key($this->request->getData(), array_flip($allowed));

            $user = $usersTable->patchEntity($user, $data, ['validate' => 'profileEdit']);

            if ($usersTable->save($user)) {
                $this->Flash->success('Your profile has been updated.');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Could not update your profile. Please check the fields and try again.');
        }

        $this->set(compact('user'));
    }

    public function wishlist()
    {
        $identity = $this->Authentication->getIdentity();
        $session  = $this->request->getSession();

        if (!$identity) {
            $guestIds = $session->read('GuestWishlist') ?? [];
            if (!empty($guestIds)) {
                $products = $this->fetchTable('Products')->find()
                    ->where(['Products.id IN' => $guestIds])
                    ->contain(['ProductImages', 'Category', 'ProductVariants'])
                    ->all();
                $wishlistItems = new \Cake\Collection\Collection(
                    array_map(fn($p) => (object)['product' => $p], $products->toArray())
                );
            } else {
                $wishlistItems = new \Cake\Collection\Collection([]);
            }
            $this->set('isGuest', true);
            $this->set(compact('wishlistItems'));
            return;
        }

        $userId = $identity->get('id');

        $wishlistItems = $this->fetchTable('Wishlists')->find()
            ->where(['Wishlists.user_id' => $userId])
            ->contain(['Products' => ['ProductImages', 'Category', 'ProductVariants']])
            ->orderByDesc('Wishlists.created')
            ->all();

        $this->set('isGuest', false);
        $this->set(compact('wishlistItems'));
    }

    public function wishlistToggle(int $id)
    {
        $this->request->allowMethod(['post']);

        $identity = $this->Authentication->getIdentity();

        if (!$identity) {
            $session  = $this->request->getSession();
            $guestIds = $session->read('GuestWishlist') ?? [];
            $productExists = $this->fetchTable('Products')->exists(['id' => $id]);
            if (!$productExists) {
                return $this->response
                    ->withStatus(404)
                    ->withType('application/json')
                    ->withStringBody(json_encode(['error' => 'Product not found']));
            }
            if (in_array($id, $guestIds)) {
                $guestIds   = array_values(array_filter($guestIds, fn($pid) => $pid !== $id));
                $wishlisted = false;
            } else {
                $guestIds[] = $id;
                $wishlisted = true;
            }
            $session->write('GuestWishlist', $guestIds);
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['wishlisted' => $wishlisted, 'guest' => true]));
        }

        $userId = $identity->get('id');
        $wishlistsTable = $this->fetchTable('Wishlists');

        $productExists = $this->fetchTable('Products')->exists(['id' => $id]);
        if (!$productExists) {
            return $this->response
                ->withStatus(404)
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => 'Product not found']));
        }

        $existing = $wishlistsTable->find()
            ->where(['user_id' => $userId, 'product_id' => $id])
            ->first();

        if ($existing) {
            $wishlistsTable->delete($existing);
            $wishlisted = false;
        } else {
            $wishlistsTable->save($wishlistsTable->newEntity(['user_id' => $userId, 'product_id' => $id]));
            $wishlisted = true;
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['wishlisted' => $wishlisted]));
    }

    public function addWishlistToCart()
    {
        $this->request->allowMethod(['post']);

        $identity = $this->Authentication->getIdentity();
        $session  = $this->request->getSession();

        if (!$identity) {
            $guestIds = $session->read('GuestWishlist') ?? [];
            if (empty($guestIds)) {
                $this->Flash->error('Your wishlist is empty.');
                return $this->redirect('/profile/wishlist');
            }
            $products = $this->fetchTable('Products')->find()
                ->where(['Products.id IN' => $guestIds])
                ->contain(['ProductVariants'])
                ->all();
            $items = new \Cake\Collection\Collection(
                array_map(fn($p) => (object)['product' => $p], $products->toArray())
            );
        } else {
            $userId = $identity->get('id');
            $items  = $this->fetchTable('Wishlists')->find()
                ->where(['Wishlists.user_id' => $userId])
                ->contain(['Products' => ['ProductVariants']])
                ->all();
        }

        $cart = $session->read('Cart') ?? [];

        $added = 0;
        $skipped = 0;

        foreach ($items as $item) {
            $product = $item->product;
            if (empty($product->product_variants)) {
                $skipped++;
                continue;
            }
            if (count($product->product_variants) !== 1) {
                $skipped++;
                continue;
            }
            $variant = $product->product_variants[0];
            if ($variant->stock < 1) {
                $skipped++;
                continue;
            }
            $key = $product->id . '_' . $variant->id;
            if (isset($cart[$key])) {
                $cart[$key]['quantity'] = min($cart[$key]['quantity'] + 1, $variant->stock);
            } else {
                $cart[$key] = ['product_id' => $product->id, 'variant_id' => $variant->id, 'quantity' => 1];
            }
            $added++;
        }

        $session->write('Cart', $cart);
        if ($added > 0 && $skipped > 0) {
            $this->Flash->success($added . ' wishlist item' . ($added === 1 ? '' : 's') . ' added to your bag. Items with size choices need to be added individually.');
        } elseif ($added > 0) {
            $this->Flash->success('Wishlist items added to your bag.');
        } else {
            $this->Flash->error('No wishlist items could be added automatically. Please choose a size for each item.');
        }
        return $this->redirect('/jewelry/cart');
    }

    public function changePassword()
    {
        return $this->redirect(['controller' => 'Auth', 'action' => 'changePassword']);
    }

    public function orders()
    {
        $userId      = $this->Authentication->getIdentity()->get('id');
        $ordersTable = $this->fetchTable('Orders');

        $query = $ordersTable->find()
            ->where(['Orders.user_id' => $userId])
            ->contain(['OrderItems'])
            ->orderByDesc('Orders.created');

        $statusFilter = $this->request->getQuery('status');
        if ($statusFilter && in_array($statusFilter, ['pending', 'paid', 'shipped', 'completed', 'cancelled'])) {
            $query->andWhere(['Orders.status' => $statusFilter]);
        }

        $orders = $this->paginate($query, ['limit' => 10]);

        $this->set(compact('orders', 'statusFilter'));
    }

    public function orderDetail(string $id)
    {
        $userId      = $this->Authentication->getIdentity()->get('id');
        $ordersTable = $this->fetchTable('Orders');

        $order = $ordersTable->find()
            ->where(['Orders.id' => $id, 'Orders.user_id' => $userId])
            ->contain(['OrderItems' => ['Products' => ['ProductImages']]])
            ->first();

        if (!$order) {
            $this->Flash->error('Order not found.');
            return $this->redirect(['action' => 'orders']);
        }

        $this->set(compact('order'));
    }
}
