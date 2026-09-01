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

        $now        = new \DateTime();
        $todayStr   = $now->format('Y-m-d');
        $weekAgo    = (clone $now)->modify('-7 days')->setTime(0, 0, 0);
        $monthStart = (clone $now)->modify('first day of this month')->setTime(0, 0, 0);

        $q        = (string)$this->request->getQuery('q', '');
        $status   = (string)$this->request->getQuery('status', '');
        $range    = (string)$this->request->getQuery('range', '');
        $dateFrom = (string)$this->request->getQuery('date_from', '');
        $dateTo   = (string)$this->request->getQuery('date_to', '');

        $query = $this->Orders->find()->contain(['OrderItems' => ['Products']]);
        if ($q !== '') {
            $query->where(['Orders.customer_email LIKE' => '%' . $q . '%']);
        }
        if ($status !== '') {
            $query->where(['Orders.status' => $status]);
        }
        if ($dateFrom !== '' || $dateTo !== '') {
            if ($dateFrom !== '') {
                $query->where(['Orders.created >=' => $dateFrom . ' 00:00:00']);
            }
            if ($dateTo !== '') {
                $query->where(['Orders.created <=' => $dateTo . ' 23:59:59']);
            }
        } elseif ($range === 'today') {
            $query->where(['Orders.created >=' => $todayStr . ' 00:00:00']);
        } elseif ($range === 'week') {
            $query->where(['Orders.created >=' => $weekAgo]);
        } elseif ($range === 'month') {
            $query->where(['Orders.created >=' => $monthStart]);
        }

        $orders = $this->paginate($query, [
            'limit' => (int)$this->request->getQuery('limit', 10),
            'order' => ['Orders.created' => 'DESC'],
        ]);

        $this->set(compact('orders', 'q', 'status', 'range', 'dateFrom', 'dateTo'));
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
