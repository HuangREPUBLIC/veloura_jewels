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
        $products = $this->Products->find()
            ->contain(['Categories', 'ProductVariants'])
            ->all();
        $lowStockProducts = $this->Products->find()
            ->contain(['Categories', 'ProductVariants'])
            ->matching('ProductVariants', function ($q) {
                return $q->where(['ProductVariants.stock <' => 5]);
            })
            ->distinct(['Products.id'])
            ->all();

        $this->set(compact('products', 'lowStockProducts'));
    }

    public function view($id = null)
    {
        $product = $this->Products->get($id, contain: ['Categories', 'ProductImages', 'ProductVariants']);
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
            $product = $this->Products->patchEntity($product, $this->request->getData(), [
                'associated' => ['ProductVariants']
            ]);
            if ($this->Products->save($product, ['associated' => ['ProductVariants']])) {
                // Handle image uploads
                $this->_saveProductImages($product->id);
                $this->Flash->success(__('The product has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }

        $categories = $this->Products->Categories->find('list', limit: 200)->all();
        $types = ['jewelry' => 'Jewelry', 'home_decor' => 'Home Decor'];
        $this->set(compact('product', 'categories', 'types'));
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

        $product = $this->Products->get($id, contain: ['Categories', 'ProductVariants', 'ProductImages']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $product = $this->Products->patchEntity($product, $this->request->getData(), [
                'associated' => ['ProductVariants']
            ]);
            if ($this->Products->save($product, ['associated' => ['ProductVariants']])) {
                // Handle new image uploads
                $this->_saveProductImages($product->id);
                // Handle image deletions
                $deleteIds = $this->request->getData('delete_images') ?? [];
                if (!empty($deleteIds)) {
                    $productImagesTable = $this->fetchTable('ProductImages');
                    foreach ($deleteIds as $imgId) {
                        $img = $productImagesTable->find()
                            ->where(['id' => $imgId, 'product_id' => $product->id])
                            ->first();
                        if ($img) {
                            $filePath = WWW_ROOT . 'img' . DS . 'products' . DS . $img->filename;
                            if (file_exists($filePath)) {
                                unlink($filePath);
                            }
                            $productImagesTable->delete($img);
                        }
                    }
                }
                $this->Flash->success(__('The product has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }

        $categories = $this->Products->Categories->find('list', limit: 200)->all();
        $types = ['jewelry' => 'Jewelry', 'home_decor' => 'Home Decor'];
        $this->set(compact('product', 'categories', 'types'));
    }

    /**
     * Handle multiple image file uploads for a product.
     */
    private function _saveProductImages(int $productId): void
    {
        $files = $this->request->getUploadedFiles();
        $uploads = $files['product_images'] ?? null;

        if (!$uploads) return;

        // Support both single file and multiple files
        if (!is_array($uploads)) {
            $uploads = [$uploads];
        }

        $productImagesTable = $this->fetchTable('ProductImages');
        $uploadDir = WWW_ROOT . 'img' . DS . 'products' . DS;
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        foreach ($uploads as $upload) {
            if ($upload->getError() !== UPLOAD_ERR_OK) continue;

            $originalName = $upload->getClientFilename();
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            // Only allow image types
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) continue;

            // Use original filename, sanitised
            $filename = preg_replace('/[^a-z0-9_\-\.]/i', '_', $originalName);

            // Move file to webroot/img/
            $upload->moveTo($uploadDir . $filename);

            // Save record
            $imageEntity = $productImagesTable->newEmptyEntity();
            $imageEntity->product_id = $productId;
            $imageEntity->filename   = $filename;
            $productImagesTable->save($imageEntity);
        }
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
