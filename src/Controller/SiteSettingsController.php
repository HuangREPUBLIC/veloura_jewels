<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

class SiteSettingsController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $identity = $this->Authentication->getIdentity();
        if (!$identity || $identity->get('role') !== 'admin') {
            $this->Flash->error('Only admins can manage site settings.');
            return $this->redirect('/');
        }
    }

    public function index()
    {
        $table = $this->fetchTable('SiteSettings');

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            foreach ($data as $key => $value) {
                $record = $table->findBySettingKey($key)->first();
                if ($record) {
                    $record->setting_value = $value;
                    $table->save($record);
                }
            }
            $this->Flash->success('Site settings saved.');
            return $this->redirect(['action' => 'index']);
        }

        $groups = [];
        foreach ($table->find()->orderBy(['group_name' => 'ASC', 'id' => 'ASC'])->all() as $row) {
            $groups[$row->group_name][] = $row;
        }

        $this->set(compact('groups'));
    }
}
