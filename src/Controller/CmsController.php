<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

class CmsController extends AppController
{
    private const FAQ_PAGE_SLUG = 'faq';

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $identity = $this->Authentication->getIdentity();
        if (!$identity || $identity->get('role') !== 'admin') {
            $this->Flash->error('Only admins can manage site content.');
            return $this->redirect('/');
        }
    }

    public function index(?string $pageSlug = null)
    {
        $cmsPagesTable = $this->fetchTable('CmsPages');

        if ($pageSlug === null) {
            $first = $cmsPagesTable->find()
                ->where(['sort_order >' => 0])
                ->orderBy(['sort_order' => 'ASC'])
                ->first();
            return $this->redirect(['action' => 'index', $first->slug]);
        }

        $pageContentsTable = $this->fetchTable('PageContents');

        $pages       = $cmsPagesTable->find()->orderBy(['sort_order' => 'ASC'])->all();
        $currentPage = $cmsPagesTable->find()->where(['slug' => $pageSlug])->first();

        if (!$currentPage) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is('post')) {
            foreach ($this->request->getData() as $key => $value) {
                $record = $pageContentsTable->find()
                    ->where(['page_id' => $currentPage->id, 'content_key' => $key])
                    ->first();
                if ($record) {
                    $record->content_value = $value;
                    $pageContentsTable->save($record);
                }
            }
            $this->Flash->success('Content saved.');
            return $this->redirect(['action' => 'index', $pageSlug]);
        }

        $contentRows = $pageContentsTable->find()
            ->where(['page_id' => $currentPage->id])
            ->orderBy(['sort_order' => 'ASC'])
            ->all()
            ->toArray();

        $faqItems = null;
        if ($pageSlug === self::FAQ_PAGE_SLUG) {
            $faqItems = $this->fetchTable('FaqItems')
                ->find()
                ->where(['is_active' => 1])
                ->orderBy(['sort_order' => 'ASC', 'id' => 'ASC'])
                ->all()
                ->toArray();
        }

        $this->set(compact('pages', 'currentPage', 'contentRows', 'pageSlug', 'faqItems'));
    }

    public function faqItemSave()
    {
        $this->request->allowMethod('post');
        $table    = $this->fetchTable('FaqItems');
        $id       = $this->request->getData('id');
        $record   = $id ? $table->get((int)$id) : $table->newEmptyEntity();
        $record   = $table->patchEntity($record, [
            'question'   => $this->request->getData('question'),
            'answer'     => $this->request->getData('answer'),
            'sort_order' => (int)$this->request->getData('sort_order', 0),
            'is_active'  => 1,
        ]);
        if ($table->save($record)) {
            $this->Flash->success('FAQ item saved.');
        } else {
            $this->Flash->error('Could not save FAQ item.');
        }
        $pageSlug = $this->request->getData('page_slug') ?: self::FAQ_PAGE_SLUG;
        return $this->redirect(['action' => 'index', $pageSlug]);
    }

    public function faqItemDelete(int $id)
    {
        $this->request->allowMethod('post');
        $table  = $this->fetchTable('FaqItems');
        $record = $table->get($id);
        if ($table->delete($record)) {
            $this->Flash->success('FAQ item deleted.');
        }
        $pageSlug = $this->request->getData('page_slug') ?: self::FAQ_PAGE_SLUG;
        return $this->redirect(['action' => 'index', $pageSlug]);
    }
}
