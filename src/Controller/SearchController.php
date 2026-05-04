<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Routing\Router;

class SearchController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['search', 'suggest']);
    }

    public function search(): void
    {
        $q = trim((string)$this->request->getQuery('q'));
        $products = [];

        if ($q !== '') {
            $products = $this->fetchTable('Products')->find()
                ->contain(['ProductImages'])
                ->where(['Products.name LIKE' => '%' . $q . '%'])
                ->orderBy(['Products.name' => 'ASC'])
                ->all();
        }

        $this->set(compact('products', 'q'));
    }

    public function suggest(): \Cake\Http\Response
    {
        $this->request->allowMethod(['get']);
        if (!$this->request->is('ajax')) {
            throw new \Cake\Http\Exception\NotFoundException();
        }
        $q = trim((string)$this->request->getQuery('q'));
        $results = [];

        if (strlen($q) >= 2) {
            $products = $this->fetchTable('Products')->find()
                ->contain(['ProductImages'])
                ->where(['Products.name LIKE' => '%' . $q . '%'])
                ->orderBy(['Products.name' => 'ASC'])
                ->limit(5)
                ->all();

            foreach ($products as $p) {
                $url = Router::url(['controller' => 'Jewelry', 'action' => 'view', $p->id]);
                $image = !empty($p->product_images)
                    ? Router::url('/img/products/' . $p->product_images[0]->filename)
                    : null;

                $results[] = [
                    'name'  => $p->name,
                    'price' => number_format((float)$p->sale_price, 2),
                    'url'   => $url,
                    'image' => $image,
                ];
            }
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody((string)json_encode(['results' => $results]));
    }
}
