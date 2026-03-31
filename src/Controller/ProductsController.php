<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

class ProductsController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $identity = $this->Authentication->getIdentity();

        if (
            !$identity ||
            !in_array($identity->get('role'), ['admin', 'part_time', 'full_time'])
        ) {
            $this->Flash->error('You do not have permission to access product management.');
            return $this->redirect('/');
        }
    }

    public function index()
    {
        $query = $this->Products->find()->contain(['Categories']);
        $products = $this->paginate($query);
        $lowStockProducts = $this->Products->find()
            ->contain(['Categories'])
            ->where(['stock <' => 5])
            ->all();

        $this->set(compact('products', 'lowStockProducts'));
    }

    public function view($id = null)
    {
        $product = $this->Products->get($id, contain: ['Categories', 'ProductImages']);
        $this->set(compact('product'));
    }

    public function add()
    {
        $identity = $this->Authentication->getIdentity();

        if (
            !$identity ||
            !in_array($identity->get('role'), ['admin', 'part_time', 'full_time'])
        ) {
            $this->Flash->error('You do not have permission to add products.');
            return $this->redirect(['action' => 'index']);
        }

        $product = $this->Products->newEmptyEntity();
        if ($this->request->is('post')) {
            $product = $this->Products->patchEntity($product, $this->request->getData());
            if ($this->Products->save($product)) {
                $this->Flash->success(__('The product has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }

        $categories = $this->Products->Categories->find('list', limit: 200)->all();
        $this->set(compact('product', 'categories'));
    }

    public function edit($id = null)
    {
        $identity = $this->Authentication->getIdentity();

        if (
            !$identity ||
            !in_array($identity->get('role'), ['admin', 'part_time', 'full_time'])
        ) {
            $this->Flash->error('You do not have permission to edit products.');
            return $this->redirect(['action' => 'index']);
        }

        $product = $this->Products->get($id, contain: ['Categories']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $product = $this->Products->patchEntity($product, $this->request->getData());
            if ($this->Products->save($product)) {
                $this->Flash->success(__('The product has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }

        $categories = $this->Products->Categories->find('list', limit: 200)->all();
        $this->set(compact('product', 'categories'));
    }

    public function delete($id = null)
    {
        $identity = $this->Authentication->getIdentity();

        if (
            !$identity ||
            !in_array($identity->get('role'), ['admin', 'part_time', 'full_time'])
        ) {
            $this->Flash->error('You do not have permission to delete products.');
            return $this->redirect(['action' => 'index']);
        }

        $this->request->allowMethod(['post', 'delete']);
        $product = $this->Products->get($id);
        if ($this->Products->delete($product)) {
            $this->Flash->success(__('The product has been deleted.'));
        } else {
            $this->Flash->error(__('The product could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
