<?php
declare(strict_types=1);

namespace App\Controller;

class OurStoryController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['index']);
    }

    public function index()
    {
        $pageContent = $this->fetchTable('PageContents')->getForPage('story');
        $this->set(compact('pageContent'));
    }
}
