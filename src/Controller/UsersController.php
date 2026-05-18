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

        $users = $this->Users->find()->all();

        $this->set(compact('users'));
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

        $user = $this->Users->get($id, contain: []);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

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
        $totalOrders = $ordersTable->find()->count();

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

        $this->set(compact('totalProducts', 'totalUsers', 'totalEnquiries', 'totalOrders', 'lowStockProducts', 'schedule', 'upcomingDays', 'weekRange'));
        $this->set('authUser', $identity);

        return null;
    }
}
