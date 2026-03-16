<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ContactSubmissions Controller
 *
 * @property \App\Model\Table\ContactSubmissionsTable $ContactSubmissions
 */
class ContactSubmissionsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->ContactSubmissions->find();
        $contactSubmissions = $this->paginate($query);

        $this->set(compact('contactSubmissions'));
    }

    /**
     * View method
     *
     * @param string|null $id Contact Submission id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $contactSubmission = $this->ContactSubmissions->get($id, contain: []);
        $this->set(compact('contactSubmission'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $contactSubmission = $this->ContactSubmissions->newEmptyEntity();
        if ($this->request->is('post')) {
            $contactSubmission = $this->ContactSubmissions->patchEntity($contactSubmission, $this->request->getData());

            //TODO - Temporary captcha still need to setup actual captcha
            $contactSubmission->captcha_passed = 0;

            if ($this->ContactSubmissions->save($contactSubmission)) {
                $this->Flash->success(__('Thanks for reaching out! We will get back to you as soon as possible.'));
                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('Something went wrong. Please check the details and try again.'));
        }
        $this->set(compact('contactSubmission'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Contact Submission id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $contactSubmission = $this->ContactSubmissions->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $contactSubmission = $this->ContactSubmissions->patchEntity($contactSubmission, $this->request->getData());
            if ($this->ContactSubmissions->save($contactSubmission)) {
                $this->Flash->success(__('The contact submission has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The contact submission could not be saved. Please, try again.'));
        }
        $this->set(compact('contactSubmission'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Contact Submission id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $contactSubmission = $this->ContactSubmissions->get($id);
        if ($this->ContactSubmissions->delete($contactSubmission)) {
            $this->Flash->success(__('The contact submission has been deleted.'));
        } else {
            $this->Flash->error(__('The contact submission could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
