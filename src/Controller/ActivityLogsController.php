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
        $this->set('productImages', $this->firstImageByProduct($activityLogs));
    }

    /**
     * First image filename for every Product the given log rows refer to, in
     * one query, so the Record column can show a thumbnail without an extra
     * lookup per row. Products deleted since the log entry simply have none.
     *
     * @param iterable<\Cake\Datasource\EntityInterface> $logs
     * @return array<int, string>
     */
    private function firstImageByProduct(iterable $logs): array
    {
        $productIds = [];
        foreach ($logs as $log) {
            if ($log->model === 'Product' && $log->model_id) {
                $productIds[(int)$log->model_id] = true;
            }
        }

        if (!$productIds) {
            return [];
        }

        $images = $this->fetchTable('ProductImages')->find()
            ->where(['ProductImages.product_id IN' => array_keys($productIds)])
            ->orderBy(['ProductImages.id' => 'ASC'])
            ->all();

        $byProduct = [];
        foreach ($images as $image) {
            $byProduct[$image->product_id] ??= $image->filename;
        }

        return $byProduct;
    }
}
