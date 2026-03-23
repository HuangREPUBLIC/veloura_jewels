<?php
declare(strict_types=1);

namespace App\Controller;


/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    // Only allow admins to edit users
    public function beforeFilter(\Cake\Event\EventInterface $event)
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
        $query = $this->Users->find();
        $users = $this->paginate($query);

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
        $user = $this->Users->get($id, contain: []);
        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
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

        if (!$identity || $identity->get('role') !== 'admin') {
            $this->Flash->error('You do not have permission to do this.');
            return $this->redirect('/');
        }

        $user = $this->Users->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
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
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function login()
    {
        $this->request->allowMethod(['get', 'post']);

        if ($this->request->is('post')) {
            $email = $this->request->getData('email');
            $password = $this->request->getData('password');

            $user = $this->Users->find()
                ->where(['email' => $email])
                ->first();

            if ($user && password_verify($password, $user->password)) {
                $this->request->getSession()->write('Auth.User', [
                    'id' => $user->id,
                    'email' => $user->email,
                    'role' => $user->role,
                ]);

                return $this->redirect([
                    'controller' => 'Users',
                    'action' => 'dashboard'
                ]);
            }

            $this->Flash->error(__('Invalid email or password.'));
        }
    }

    public function dashboard()
    {
        $identity = $this->Authentication->getIdentity();

        if (!$identity || $identity->get('role') !== 'admin') {
            $this->Flash->error('You do not have permission to access the dashboard.');
            return $this->redirect('/');
        }

        $this->set('authUser', $identity);
    }



}
