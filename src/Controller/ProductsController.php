<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Log\Log;
use Cake\Datasource\Exception\RecordNotFoundException;

class ProductsController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $identity = $this->Authentication->getIdentity();

        if (
            !$identity ||
            !in_array($identity->get('role'), ['admin', 'staff'])
        ) {
            $this->Flash->error('You do not have permission to access product management.');
            return $this->redirect('/');
        }
    }

    public function index()
    {
        $products = $this->Products->find()
            ->contain(['Category', 'ProductVariants'])
            ->all();
        $lowStockProducts = $this->Products->find()
            ->contain(['Category', 'ProductVariants'])
            ->matching('ProductVariants', function ($q) {
                return $q->where(['ProductVariants.stock <' => 5]);
            })
            ->distinct(['Products.id'])
            ->all();

        $this->set(compact('products', 'lowStockProducts'));
    }

    public function view($id = null)
    {
        $product = $this->Products->get($id, contain: ['Category', 'ProductImages', 'ProductVariants']);
        $this->set(compact('product'));
    }

    public function add()
    {
        $identity = $this->Authentication->getIdentity();

        if (
            !$identity ||
            !in_array($identity->get('role'), ['admin'])
        ) {
            $this->Flash->error('You do not have permission to add products.');
            return $this->redirect(['action' => 'index']);
        }

        $product = $this->Products->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $typeValue = $data['type'] ?? '';

            if (($data['category_id'] ?? '') === '__new__') {
                $newCatName = trim($data['new_category_name'] ?? '');
                if ($newCatName !== '' && $typeValue !== '') {
                    $categoriesTable = $this->fetchTable('Categories');
                    $newCat = $categoriesTable->newEntity(['name' => $newCatName, 'type' => $typeValue]);
                    $savedCat = $categoriesTable->save($newCat);
                    if ($savedCat) {
                        $data['category_id'] = $savedCat->id;
                    }
                }
            }

            $product = $this->Products->patchEntity($product, $data, ['associated' => ['ProductVariants']]);
            if ($this->Products->save($product, ['associated' => ['ProductVariants']])) {
                $this->_saveProductImages($product->id);
                $this->Flash->success(__('The product has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }

        [$categories, $categoriesJson, $types] = $this->getProductFormCategories();

        $preselectedType = $this->request->getQuery('type') ?? '';
        $this->set(compact('product', 'categories', 'categoriesJson', 'types', 'preselectedType'));
    }

    public function edit($id = null)
    {
        $identity = $this->Authentication->getIdentity();

        if (
            !$identity ||
            !in_array($identity->get('role'), ['admin'])
        ) {
            $this->Flash->error('You do not have permission to edit products.');
            return $this->redirect(['action' => 'index']);
        }

        $from = $this->request->getQuery('from');

        $product = $this->Products->get($id, contain: ['Category', 'ProductVariants', 'ProductImages']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $from = $data['from'] ?? $from;
            $typeValue = $data['type'] ?? '';

            if (($data['category_id'] ?? '') === '__new__') {
                $newCatName = trim($data['new_category_name'] ?? '');
                if ($newCatName !== '' && $typeValue !== '') {
                    $categoriesTable = $this->fetchTable('Categories');
                    $newCat = $categoriesTable->newEntity(['name' => $newCatName, 'type' => $typeValue]);
                    $savedCat = $categoriesTable->save($newCat);
                    if ($savedCat) {
                        $data['category_id'] = $savedCat->id;
                    }
                }
            }

            $product = $this->Products->patchEntity($product, $data, ['associated' => ['ProductVariants']]);
            if ($this->Products->save($product, ['associated' => ['ProductVariants']])) {
                $this->_saveProductImages($product->id);
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
                if ($from === 'dashboard') {
                    return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
                }
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }

        $this->set('from', $from);

        [$categories, $categoriesJson, $types] = $this->getProductFormCategories();
        $this->set(compact('product', 'categories', 'categoriesJson', 'types'));
    }

    private function getProductFormCategories(): array
    {
        $categoriesRaw = $this->fetchTable('Categories')->find()
            ->select(['id', 'name', 'type'])
            ->orderBy(['type' => 'ASC', 'name' => 'ASC'])
            ->all()
            ->toArray();

        $categories = [];
        foreach ($categoriesRaw as $cat) {
            $categories[$cat->id] = $cat->name;
        }

        $categoriesJson = json_encode(array_map(fn($cat) => [
            'id'   => $cat->id,
            'name' => $cat->name,
            'type' => $cat->type,
        ], $categoriesRaw), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?: '[]';

        $types = [];
        foreach (array_unique(array_map(fn($cat) => $cat->type, $categoriesRaw)) as $key) {
            if (!empty($key)) {
                $types[$key] = ucwords(str_replace('_', ' ', $key));
            }
        }
        if (empty($types)) {
            $types = ['jewelry' => 'Jewelry', 'home_decor' => 'Home Decor'];
        }

        return [$categories, $categoriesJson, $types];
    }

    /**
     * Handle multiple image file uploads for a product.
     */
    private function _saveProductImages(int $productId): void
    {
        $files = $this->request->getUploadedFiles();
        $uploads = $files['product_images'] ?? null;

        if (!$uploads) return;

        if (!is_array($uploads)) {
            $uploads = [$uploads];
        }

        $productImagesTable = $this->fetchTable('ProductImages');
        $uploadDir = WWW_ROOT . 'img' . DS . 'products' . DS;
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        foreach ($uploads as $upload) {
            if ($upload->getError() !== UPLOAD_ERR_OK) continue;
            if ($upload->getSize() > $maxSize) continue;

            $originalName = $upload->getClientFilename();
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) continue;

            $tmpPath = $upload->getStream()->getMetadata('uri');
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            if (!in_array($finfo->file($tmpPath), $allowedMimes)) continue;

            $filename = preg_replace('/[^a-z0-9_\-\.]/i', '_', $originalName);
            $upload->moveTo($uploadDir . $filename);

            $imageEntity = $productImagesTable->newEmptyEntity();
            $imageEntity->product_id = $productId;
            $imageEntity->filename   = $filename;
            $productImagesTable->save($imageEntity);
        }
    }

    public function toggleFeatured($id = null)
    {
        $this->request->allowMethod(['post']);

        $identity = $this->Authentication->getIdentity();
        if (!$identity || $identity->get('role') !== 'admin') {
            return $this->response->withStatus(403)
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => 'Forbidden']));
        }

        $product = $this->Products->get($id);
        $product->featured = !$product->featured;
        $this->Products->save($product);

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['featured' => (bool)$product->featured]));
    }

    public function delete($id = null)
    {
        $identity = $this->Authentication->getIdentity();

        if (
            !$identity ||
            !in_array($identity->get('role'), ['admin'])
        ) {
            $this->Flash->error('You do not have permission to delete products.');
            return $this->redirect(['action' => 'index']);
        }

        $this->request->allowMethod(['post', 'delete']);

        try {
            $product = $this->Products->get($id);
        } catch (RecordNotFoundException $e) {
            $this->Flash->error(__('Product not found.'));
            return $this->redirect(['action' => 'index']);
        }

        $adminId = $identity->get('id');
        $adminEmail = $identity->get('email');

        if ($this->Products->delete($product)) {
            Log::write('info', "Product deleted: id={$product->id}, name=\"{$product->name}\" by admin id={$adminId} ({$adminEmail})");
            $this->Flash->success(__('The product has been deleted.'));
        } else {
            $this->Flash->error(__('The product could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
