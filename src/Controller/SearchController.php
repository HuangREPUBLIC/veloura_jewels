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

        $productsTable = $this->fetchTable('Products');

        if ($q !== '') {
            $bestSalesIds = array_unique(array_merge(
                collection($productsTable->find('bestSales', productType: 'jewelry', limit: 4)->all())->extract('id')->toList(),
                collection($productsTable->find('bestSales', productType: 'home_decor', limit: 4)->all())->extract('id')->toList()
            ));

            $products = $productsTable->find()
                ->contain(['ProductImages'])
                ->where(['Products.name LIKE' => '%' . $q . '%'])
                ->orderBy(['Products.name' => 'ASC'])
                ->all();

            foreach ($products as $product) {
                $product->is_bestsales = in_array($product->id, $bestSalesIds);
            }
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

        $productsTable = $this->fetchTable('Products');

        if (strlen($q) >= 2) {
            $bestSalesIds = array_unique(array_merge(
                collection($productsTable->find('bestSales', productType: 'jewelry', limit: 4)->all())->extract('id')->toList(),
                collection($productsTable->find('bestSales', productType: 'home_decor', limit: 4)->all())->extract('id')->toList()
            ));
            $products = $productsTable->find()
                ->contain(['ProductImages'])
                ->where(['Products.name LIKE' => '%' . $q . '%'])
                ->orderBy(['Products.name' => 'ASC'])
                ->limit(4)
                ->all();

            foreach ($products as $p) {
                $url = Router::url(['controller' => 'Jewelry', 'action' => 'view', $p->id]);

                $images = array_map(
                    fn($img) => Router::url('/img/products/' . $img->filename),
                    $p->product_images
                );

                $results[] = [
                    'name'         => $p->name,
                    'price'        => number_format((float)$p->sale_price, 2),
                    'url'          => $url,
                    'images'       => $images,
                    'featured'     => !empty($p->featured),
                    'is_bestsales' => in_array($p->id, $bestSalesIds),
                ];
            }
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody((string)json_encode(['results' => $results]));
    }
}
