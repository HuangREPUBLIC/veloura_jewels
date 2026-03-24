<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Event\EventInterface;
use Cake\Mailer\Mailer;

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

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['add']);
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
        $contactSubmission = $this->ContactSubmissions->get($id, contain: ['ContactReplies']);
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
    public function reply($id = null)
    {
        $contactSubmission = $this->ContactSubmissions->get($id);

        if ($this->request->is(['post', 'put'])) {
            $subject = $this->request->getData('subject');
            $message = $this->request->getData('message');

            try {
                $mailer = new Mailer('default');
                $mailer
                    ->setTo($contactSubmission->email)
                    ->setSubject($subject)
                    ->deliver($message);

                $contactRepliesTable = $this->fetchTable('ContactReplies');
                $reply = $contactRepliesTable->newEntity([
                    'contact_submission_id' => $contactSubmission->id,
                    'subject' => $subject,
                    'message' => $message,
                    'sent_at' => date('Y-m-d H:i:s'),
                ]);

                $contactRepliesTable->save($reply);

                $contactSubmission->is_replied = true;
                $this->ContactSubmissions->save($contactSubmission);

                $this->Flash->success('Reply sent successfully.');
                return $this->redirect(['action' => 'view', $id]);
            } catch (\Exception $e) {
                $this->Flash->error('Could not send email. Please try again.');
            }
        }

        $this->set(compact('contactSubmission'));
    }
    public function replies($id = null)
    {
        $contactSubmission = $this->ContactSubmissions->get($id, contain: ['ContactReplies']);
        $this->set(compact('contactSubmission'));
    }
}
