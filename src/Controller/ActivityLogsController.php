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

        $this->viewBuilder()->setLayout('admin');

        $filterModel = $this->request->getQuery('model');
        $filterAction = $this->request->getQuery('action');

        $query = $this->fetchTable('ActivityLogs')->find()
            ->where(['ActivityLogs.is_archived' => false]);
        if (!empty($filterModel)) {
            $query->where(['ActivityLogs.model' => $filterModel]);
        }
        if (!empty($filterAction)) {
            $query->where(['ActivityLogs.action' => $filterAction]);
        }

        $activityLogs = $this->paginate($query, [
            'limit' => (int)$this->request->getQuery('limit', 25),
            'order' => ['ActivityLogs.created' => 'DESC'],
        ]);

        $this->set(compact('activityLogs', 'filterModel', 'filterAction'));
    }
}
