<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    // Only allow authenticated users; specific admin checks are inside actions
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions([]);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $identity = $this->Authentication->getIdentity();

        if (!$identity || !in_array($identity->get('role'), ['admin'])) {
            $this->Flash->error('You do not have permission to view users.');
            return $this->redirect('/');
        }

        $this->viewBuilder()->setLayout('admin');

        $q = (string)$this->request->getQuery('q', '');
        $query = $this->Users->find();
        if ($q !== '') {
            $query->where([
                'OR' => [
                    'first_name LIKE' => '%' . $q . '%',
                    'last_name LIKE' => '%' . $q . '%',
                    'email LIKE' => '%' . $q . '%',
                ],
            ]);
        }
        $users = $this->paginate($query, ['limit' => (int)$this->request->getQuery('limit', 10)]);

        $this->set(compact('users', 'q'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $identity = $this->Authentication->getIdentity();

        if (!$identity || !in_array($identity->get('role'), ['admin', 'staff'])) {
            $this->Flash->error('You do not have permission to view this user.');
            return $this->redirect('/');
        }

        $this->viewBuilder()->setLayout('admin');

        $user = $this->Users->get($id, contain: []);
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $identity = $this->Authentication->getIdentity();

        if (!$identity || !in_array($identity->get('role'), ['admin'])) {
            $this->Flash->error('You do not have permission to do this.');
            return $this->redirect('/');
        }

        $this->viewBuilder()->setLayout('admin');

        $user = $this->Users->get($id, contain: []);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if ((int)$id === 6 && isset($data['role'])) {
                unset($data['role']);
            }

            if ($identity->get('role') === 'staff') {
                if (isset($data['role']) && $data['role'] === 'admin') {
                    $this->Flash->error('You do not have permission to assign the admin role.');
                    return $this->redirect(['action' => 'index']);
                }
            }

            if ($identity->get('role') === 'customer') {
                $this->Flash->error('You do not have permission to edit users.');
                return $this->redirect(['action' => 'index']);
            }

            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                if ($identity && $identity->get('id') === $user->id) {
                    $this->Flash->success('Your account has been updated. Please log in again.');
                    return $this->redirect(['controller' => 'Auth', 'action' => 'logout']);
                }

                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }

        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $identity = $this->Authentication->getIdentity();

        if (!$identity || $identity->get('role') !== 'admin') {
            $this->Flash->error('You do not have permission to delete users.');
            return $this->redirect('/');
        }

        $this->request->allowMethod(['post', 'delete']);

        if ((int)$id === 6) {
            $this->Flash->error('This account cannot be deleted.');
            return $this->redirect(['action' => 'index']);
        }

        $user = $this->Users->get($id);

        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function dashboard()
    {
        $identity = $this->Authentication->getIdentity();

        if (!$identity || !in_array($identity->get('role'), ['admin', 'staff'])) {
            $this->Flash->error('You do not have permission to access the dashboard.');
            return $this->redirect('/');
        }

        $this->viewBuilder()->setLayout('admin');

        $productsTable = $this->fetchTable('Products');
        $usersTable = $this->fetchTable('Users');
        $contactSubmissionsTable = $this->fetchTable('ContactSubmissions');

        $totalProducts = $productsTable->find()->count();
        $totalUsers = $usersTable->find()->count();
        $totalEnquiries = $contactSubmissionsTable->find()->count();
        $lowStockProducts = $productsTable
            ->find()
            ->contain(['ProductVariants'])
            ->matching('ProductVariants', function ($q) {
                return $q->where(['ProductVariants.stock <' => 5]);
            })
            ->distinct(['Products.id'])
            ->all();

        $ordersTable = $this->fetchTable('Orders');

        // Same revenue-by-period rollup as Orders/index.php's "Revenue Summary"
        // panel, so the dashboard can show it without duplicating the plain
        // Orders count the sidebar link already covers.
        $now        = new \DateTime();
        $todayStr   = $now->format('Y-m-d');
        $weekAgo    = (clone $now)->modify('-7 days')->setTime(0, 0, 0);
        $monthStart = (clone $now)->modify('first day of this month')->setTime(0, 0, 0);

        $revenueStats = [
            'today' => ['sales' => 0, 'profit' => 0],
            'week'  => ['sales' => 0, 'profit' => 0],
            'month' => ['sales' => 0, 'profit' => 0],
            'all'   => ['sales' => 0, 'profit' => 0],
        ];

        // Sales trend buckets for the dashboard chart's Week/Month/Year tabs -
        // zero-filled so empty periods still render bars.
        $trendStart = (clone $now)->modify('-29 days')->setTime(0, 0, 0);
        $dailySales = [];
        for ($i = 0; $i < 30; $i++) {
            $day = (clone $trendStart)->modify("+{$i} days");
            $dailySales[$day->format('Y-m-d')] = 0.0;
        }

        $monthlySales = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = (clone $now)->modify("-{$i} months");
            $monthlySales[$m->format('Y-m')] = 0.0;
        }

        $paidOrders = $ordersTable->find()
            ->where(['Orders.status' => 'paid'])
            ->contain(['OrderItems' => ['Products']])
            ->all();

        foreach ($paidOrders as $order) {
            $profit = 0;
            foreach ($order->order_items as $item) {
                if ($item->product) {
                    $profit += ($item->unit_price - $item->product->purchase_price) * $item->quantity;
                }
            }

            $d = $order->created;
            $dKey = $d->format('Y-m-d');
            $revenueStats['all']['sales']  += $order->total_amount;
            $revenueStats['all']['profit'] += $profit;
            if ($dKey === $todayStr) {
                $revenueStats['today']['sales']  += $order->total_amount;
                $revenueStats['today']['profit'] += $profit;
            }
            if ($d >= $weekAgo) {
                $revenueStats['week']['sales']  += $order->total_amount;
                $revenueStats['week']['profit'] += $profit;
            }
            if ($d >= $monthStart) {
                $revenueStats['month']['sales']  += $order->total_amount;
                $revenueStats['month']['profit'] += $profit;
            }
            if (isset($dailySales[$dKey])) {
                $dailySales[$dKey] += (float)$order->total_amount;
            }
            $mKey = $d->format('Y-m');
            if (isset($monthlySales[$mKey])) {
                $monthlySales[$mKey] += (float)$order->total_amount;
            }
        }

        $schedule     = [];
        $upcomingDays = [];
        $weekRange    = null;

        if ($identity->get('role') === 'staff') {
            $schedulesTable = $this->fetchTable('Schedules');
            $today          = new \DateTime('today');

            // Build rolling 7 days starting from today
            for ($i = 0; $i < 7; $i++) {
                $upcomingDays[] = (clone $today)->modify("+{$i} days");
            }

            // Collect the (at most 2) week_starts covered by these 7 days
            $weekStarts = [];
            foreach ($upcomingDays as $day) {
                $isoDow  = (int)$day->format('N');
                $monday  = (clone $day)->modify('-' . ($isoDow - 1) . ' days');
                $weekStarts[$monday->format('Y-m-d')] = true;
            }

            // Fetch all matching Schedule rows
            $scheduleMap = [];
            foreach ($schedulesTable->find()->where([
                'user_id'       => $identity->get('id'),
                'week_start IN' => array_keys($weekStarts),
            ])->all() as $row) {
                $key = $row->week_start->format('Y-m-d') . ':' . $row->day_of_week;
                $scheduleMap[$key] = $row;
            }

            // Index by date string for easy template lookup
            foreach ($upcomingDays as $day) {
                $isoDow  = (int)$day->format('N');
                $monday  = (clone $day)->modify('-' . ($isoDow - 1) . ' days');
                $appDow  = (int)$day->format('N'); // 1=Mon ... 7=Sun
                $key     = $monday->format('Y-m-d') . ':' . $appDow;
                $schedule[$day->format('Y-m-d')] = $scheduleMap[$key] ?? null;
            }

            $weekRange = $upcomingDays[0]->format('j M') . ' - ' . $upcomingDays[6]->format('j M Y');
        }

        $revenueTrendWeek = [];
        foreach (array_slice($dailySales, -7, 7, true) as $dateStr => $amount) {
            $revenueTrendWeek[] = ['date' => $dateStr, 'amount' => $amount, 'label' => (new \DateTime($dateStr))->format('D')];
        }
        $revenueTrendMonth = [];
        foreach ($dailySales as $dateStr => $amount) {
            $revenueTrendMonth[] = ['date' => $dateStr, 'amount' => $amount, 'label' => (new \DateTime($dateStr))->format('j M')];
        }
        $revenueTrendYear = [];
        foreach ($monthlySales as $monthStr => $amount) {
            $revenueTrendYear[] = ['date' => $monthStr, 'amount' => $amount, 'label' => (new \DateTime($monthStr . '-01'))->format('M')];
        }

        $topSellingProducts = $this->fetchTable('OrderItems')->find();
        $topSellingProducts
            ->select([
                'product_id' => 'OrderItems.product_id',
                'units_sold' => $topSellingProducts->func()->sum('OrderItems.quantity'),
            ])
            ->innerJoinWith('Orders', fn ($q) => $q->where(['Orders.status' => 'paid']))
            ->groupBy('OrderItems.product_id')
            ->orderBy(['units_sold' => 'DESC'])
            ->limit(5);
        $topSellingProducts = $topSellingProducts->all();

        $topProductIds = array_column($topSellingProducts->toList(), 'product_id');
        $topProductsById = [];
        if ($topProductIds) {
            foreach ($productsTable->find()->where(['id IN' => $topProductIds])->contain(['ProductImages']) as $p) {
                $topProductsById[$p->id] = $p;
            }
        }

        $this->set(compact(
            'totalProducts',
            'totalUsers',
            'totalEnquiries',
            'revenueStats',
            'revenueTrendWeek',
            'revenueTrendMonth',
            'revenueTrendYear',
            'topSellingProducts',
            'topProductsById',
            'lowStockProducts',
            'schedule',
            'upcomingDays',
            'weekRange'
        ));
        $this->set('authUser', $identity);

        return null;
    }
}
