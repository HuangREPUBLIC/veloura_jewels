<?php
declare(strict_types=1);

namespace App\Controller;

class FaqController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['index']);
    }

    public function index()
    {
        $faqs = $this->fetchTable('FaqItems')
            ->find()
            ->where(['is_active' => 1])
            ->orderBy(['sort_order' => 'ASC', 'id' => 'ASC'])
            ->all();

        $pageContent = $this->fetchTable('PageContents')->getForPage('faq');

        $this->set(compact('faqs', 'pageContent'));
    }
}
