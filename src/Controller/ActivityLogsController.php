<?php
declare(strict_types=1);

namespace App\Controller;

class ActivityLogsController extends AppController
{
    public function index()
    {
        $identity = $this->Authentication->getIdentity();
        if (!$identity || $identity->get('role') !== 'admin') {
            $this->Flash->error('You do not have permission to access this page.');
            return $this->redirect('/dashboard');
        }

        $this->Html->css('https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css', ['block' => true]);
        $this->Html->script('https://code.jquery.com/jquery-3.6.0.min.js', ['block' => true]);
        $this->Html->script('https://cdn.datatables.net/2.2.2/js/dataTables.min.js', ['block' => true]);

        $conditions = [];
        $filterModel = $this->request->getQuery('model');
        $filterAction = $this->request->getQuery('action');

        if (!empty($filterModel)) {
            $conditions['ActivityLogs.model'] = $filterModel;
        }
        if (!empty($filterAction)) {
            $conditions['ActivityLogs.action'] = $filterAction;
        }

        $this->paginate = [
            'limit'  => 50,
            'order'  => ['ActivityLogs.created' => 'DESC'],
            'conditions' => $conditions,
        ];

        $activityLogs = $this->paginate($this->fetchTable('ActivityLogs'));

        $this->set(compact('activityLogs', 'filterModel', 'filterAction'));
    }
}
