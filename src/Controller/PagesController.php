<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;

class PagesController extends AppController
{

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['display', 'location']);
    }

    public function location(): void
    {
        $this->set('pageContent', $this->fetchTable('PageContents')->getForPage('location'));
    }

    public function display(string ...$path): ?Response
    {
        if (!$path) {
            return $this->redirect('/');
        }
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        $this->set(compact('page', 'subpage'));

        if ($page === 'home') {
            $productsTable = $this->fetchTable('Products');

            $featuredJewelry = $productsTable->find()
                ->contain(['ProductImages', 'Category'])
                ->where(['Category.type' => 'jewelry', 'Products.featured' => true])
                ->orderBy(['Products.id' => 'DESC'])
                ->limit(3)
                ->all();

            $featuredHomeDecor = $productsTable->find()
                ->contain(['ProductImages', 'Category'])
                ->where(['Category.type' => 'home_decor', 'Products.featured' => true])
                ->orderBy(['Products.id' => 'DESC'])
                ->limit(3)
                ->all();

            $pageContent = $this->fetchTable('PageContents')->getForPage('home');
            $identity = $this->request->getAttribute('identity');
            $wishlistIds = [];
            if ($identity) {
                $wishlistIds = $this->fetchTable('Wishlists')->find()
                    ->where(['user_id' => $identity->get('id')])
                    ->all()->extract('product_id')->toList();
            }
            $this->set(compact('featuredJewelry', 'featuredHomeDecor', 'pageContent', 'wishlistIds'));
        }

        try {
            return $this->render(implode('/', $path));
        } catch (MissingTemplateException $exception) {
            if (Configure::read('debug')) {
                throw $exception;
            }
            throw new NotFoundException();
        }
    }
}
