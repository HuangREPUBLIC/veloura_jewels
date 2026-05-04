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
        $this->Authentication->addUnauthenticatedActions(['display']);
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

            $bestSalesJewelry = $productsTable
                ->find('bestSales', productType: 'jewelry', limit: 4)
                ->all();

            $bestSalesHomeDecor = $productsTable
                ->find('bestSales', productType: 'home_decor', limit: 4)
                ->all();

            $bestSalesIds = array_unique(array_merge(
                collection($bestSalesJewelry)->extract('id')->toList(),
                collection($bestSalesHomeDecor)->extract('id')->toList()
            ));

            $featuredProducts = $productsTable->find()
                ->contain(['ProductImages', 'Categories'])
                ->orderBy(['Products.id' => 'DESC'])
                ->limit(4)
                ->all();

            foreach ($featuredProducts as $product) {
                $product->is_bestsales = in_array($product->id, $bestSalesIds);
            }

            $this->set(compact('featuredProducts'));
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
