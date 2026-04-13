<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Profile Controller
 * Handles customer-facing profile management and order history.
 *
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 */
class ProfileController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        // All profile actions require login — no unauthenticated access
    }

    /**
     * Redirect admins away from the profile area before any action runs.
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);

        $user = $this->Authentication->getIdentity();
        if ($user && in_array($user->get('role'), ['admin', 'full_time', 'part_time'])) {
            $event->stopPropagation();
            $this->response = $this->redirect('/dashboard');
        }
    }

    /**
     * Profile index — personal info summary + recent orders
     */
    public function index()
    {
        $userId = $this->Authentication->getIdentity()->get('id');

        $usersTable  = $this->fetchTable('Users');
        $ordersTable = $this->fetchTable('Orders');

        $user = $usersTable->get($userId);

        // Grab the 5 most recent orders for the dashboard summary
        $recentOrders = $ordersTable->find()
            ->where(['Orders.user_id' => $userId])
            ->contain(['OrderItems'])
            ->orderByDesc('Orders.created')
            ->limit(5)
            ->all();

        $this->set(compact('user', 'recentOrders'));
    }

    /**
     * Edit personal info (name, phone, address)
     */
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

    /**
     * Change password — handled by AuthController::changePassword
     */
    public function changePassword()
    {
        return $this->redirect(['controller' => 'Auth', 'action' => 'changePassword']);
    }

    /**
     * Full order history
     */
    public function orders()
    {
        $userId      = $this->Authentication->getIdentity()->get('id');
        $ordersTable = $this->fetchTable('Orders');

        $query = $ordersTable->find()
            ->where(['Orders.user_id' => $userId])
            ->contain(['OrderItems'])
            ->orderByDesc('Orders.created');

        // Optional status filter
        $statusFilter = $this->request->getQuery('status');
        if ($statusFilter && in_array($statusFilter, ['pending', 'paid', 'shipped', 'completed', 'cancelled'])) {
            $query->andWhere(['Orders.status' => $statusFilter]);
        }

        $orders = $this->paginate($query, ['limit' => 10]);

        $this->set(compact('orders', 'statusFilter'));
    }

    /**
     * Single order detail — only accessible by the owning customer
     */
    public function orderDetail(string $id)
    {
        $userId      = $this->Authentication->getIdentity()->get('id');
        $ordersTable = $this->fetchTable('Orders');

        $order = $ordersTable->find()
            ->where(['Orders.id' => $id, 'Orders.user_id' => $userId])
            ->contain(['OrderItems'])
            ->first();

        if (!$order) {
            $this->Flash->error('Order not found.');
            return $this->redirect(['action' => 'orders']);
        }

        $this->set(compact('order'));
    }
}
