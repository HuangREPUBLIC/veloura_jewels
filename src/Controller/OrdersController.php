<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Orders Controller
 *
 * @property \App\Model\Table\OrdersTable $Orders
 */
class OrdersController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions([]);
    }

    /**
     * Index method - list all orders
     */
    public function index()
    {
        $identity = $this->Authentication->getIdentity();

        if (!$identity || !in_array($identity->get('role'), ['admin', 'staff'])) {
            $this->Flash->error('You do not have permission to view orders.');
            return $this->redirect('/');
        }

        $this->viewBuilder()->setLayout('admin');

        $orders = $this->Orders->find()
            ->contain(['OrderItems' => ['Products']])
            ->orderBy(['Orders.created' => 'DESC'])
            ->all();

        $this->set(compact('orders'));
    }

    /**
     * View method - view a single order with its items
     */
    public function view($id = null)
    {
        $identity = $this->Authentication->getIdentity();

        if (!$identity || !in_array($identity->get('role'), ['admin', 'staff'])) {
            $this->Flash->error('You do not have permission to view this order.');
            return $this->redirect('/');
        }

        $this->viewBuilder()->setLayout('admin');

        $order = $this->Orders->get($id, contain: ['OrderItems' => ['Products']]);

        $this->set(compact('order'));
    }
}
