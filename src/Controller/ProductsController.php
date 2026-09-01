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

        $this->viewBuilder()->setLayout('admin');
    }

    public function index()
    {
        $q = (string)$this->request->getQuery('q', '');

        $query = $this->Products->find()->contain(['Category', 'ProductVariants']);
        if ($q !== '') {
            $query->where(['Products.name LIKE' => '%' . $q . '%']);
        }
        $products = $this->paginate($query, ['limit' => (int)$this->request->getQuery('limit', 10)]);

        $this->set(compact('products', 'q'));
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

            $this->resolveNewCategory($data, $typeValue);
            $this->resolveNewSizes($data);

            $variants = array_filter($data['product_variants'] ?? [], fn($v) => !empty($v['size']));
            if (empty($variants)) {
                $this->Flash->error(__('Please add at least one size and stock entry.'));
                [$categories, $categoriesJson, $types] = $this->getProductFormCategories();
                $preselectedType = $this->request->getQuery('type') ?? '';
                $this->set(compact('product', 'categories', 'categoriesJson', 'types', 'preselectedType'));
                return;
            }

            $product = $this->Products->patchEntity($product, $data, ['associated' => ['ProductVariants']]);
            if ($this->Products->save($product, ['associated' => ['ProductVariants']])) {
                $this->_saveProductImages($product->id);
                $this->logActivity('Product', $product->id, 'created', $product->name, [
                    'name'           => $product->name,
                    'type'           => $product->type,
                    'sale_price'     => (string)$product->sale_price,
                    'purchase_price' => (string)$product->purchase_price,
                    'supplier_email' => $product->supplier_email,
                    'description'    => $product->description,
                ]);
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

            $this->resolveNewCategory($data, $typeValue);
            $this->resolveNewSizes($data);

            $variants = array_filter($data['product_variants'] ?? [], fn($v) => !empty($v['size']));
            if (empty($variants)) {
                $this->Flash->error(__('Please add at least one size and stock entry.'));
                [$categories, $categoriesJson, $types] = $this->getProductFormCategories();
                $this->set(compact('product', 'categories', 'categoriesJson', 'types'));
                return;
            }

            $trackFields = ['name', 'sale_price', 'purchase_price', 'description', 'story', 'supplier_email', 'featured', 'category_id'];

            $variantsBefore = [];
            foreach ($product->product_variants ?? [] as $v) {
                $variantsBefore[] = ['size' => $v->size, 'stock' => (int)$v->stock];
            }

            $product = $this->Products->patchEntity($product, $data, ['associated' => ['ProductVariants']]);

            $changes = [];
            foreach ($product->getDirty() as $field) {
                if (in_array($field, $trackFields)) {
                    $changes[$field] = [
                        'from' => $product->getOriginal($field),
                        'to'   => $product->$field,
                    ];
                }
            }

            if ($this->Products->save($product, ['associated' => ['ProductVariants']])) {
                $productImagesTable = $this->fetchTable('ProductImages');
                $imagesBefore = array_map(
                    fn($e) => $e->filename,
                    $productImagesTable->find()->where(['product_id' => $product->id])->select(['filename'])->toArray()
                );

                $this->_saveProductImages($product->id);

                $deleteIds = $this->request->getData('delete_images') ?? [];
                if (!empty($deleteIds)) {
                    $toDelete = $productImagesTable->find()
                        ->where(['id IN' => $deleteIds, 'product_id' => $product->id])
                        ->toArray();
                    foreach ($toDelete as $img) {
                        $filePath = WWW_ROOT . 'img' . DS . 'products' . DS . $img->filename;
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                        $productImagesTable->delete($img);
                    }
                }

                $imagesAfter = array_map(
                    fn($e) => $e->filename,
                    $productImagesTable->find()->where(['product_id' => $product->id])->select(['filename'])->toArray()
                );

                if ($imagesBefore !== $imagesAfter) {
                    $changes['images'] = ['from' => $imagesBefore, 'to' => $imagesAfter];
                }

                $variantsAfter = array_map(
                    fn($v) => ['size' => $v->size, 'stock' => (int)$v->stock],
                    $product->product_variants ?? []
                );

                if ($variantsBefore !== $variantsAfter) {
                    $changes['sizes'] = ['from' => $variantsBefore, 'to' => $variantsAfter];
                }

                if (!empty($changes)) {
                    $this->logActivity('Product', $product->id, 'updated', $product->name, $changes);
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
            ->select(['id', 'name', 'type', 'sizes'])
            ->orderBy(['type' => 'ASC', 'name' => 'ASC'])
            ->all()
            ->toArray();

        $categories = [];
        foreach ($categoriesRaw as $cat) {
            $categories[$cat->id] = $cat->name;
        }

        $categoriesJson = json_encode(array_map(fn($cat) => [
            'id'    => $cat->id,
            'name'  => $cat->name,
            'type'  => $cat->type,
            'sizes' => $cat->sizes ?? ['One Size'],
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

    private function resolveNewCategory(array &$data, string $typeValue): void
    {
        if (($data['category_id'] ?? '') !== '__new__') return;
        $newCatName = trim($data['new_category_name'] ?? '');
        if ($newCatName === '' || $typeValue === '') return;
        $categoriesTable = $this->fetchTable('Categories');
        $newCat = $categoriesTable->newEntity(['name' => $newCatName, 'type' => $typeValue, 'sizes' => ['One Size']]);
        $savedCat = $categoriesTable->save($newCat);
        if ($savedCat) {
            $data['category_id'] = $savedCat->id;
        }
    }

    // Apply new-size entries in variant rows, updating the category's sizes list.
    private function resolveNewSizes(array &$data): void
    {
        $catId = (int)($data['category_id'] ?? 0);
        if ($catId <= 0) return;

        $categoriesTable = $this->fetchTable('Categories');
        $cat = $categoriesTable->get($catId);
        $sizes = $cat->sizes ?? ['One Size'];
        $updated = false;

        foreach ($data['product_variants'] ?? [] as $i => $variant) {
            if (($variant['size'] ?? '') !== '__new__') continue;
            $newName = trim($variant['new_size_name'] ?? '');
            if ($newName !== '' && !in_array($newName, $sizes)) {
                $sizes[] = $newName;
                $updated = true;
            }
            $data['product_variants'][$i]['size'] = $newName !== '' ? $newName : '';
            unset($data['product_variants'][$i]['new_size_name']);
        }

        if ($updated) {
            $cat->sizes = $sizes;
            $categoriesTable->save($cat);
        }
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
        $wasFeature = $product->featured;
        $product->featured = !$wasFeature;
        $this->Products->save($product);
        $this->logActivity('Product', $product->id, 'updated', $product->name, [
            'featured' => ['from' => $wasFeature, 'to' => $product->featured],
        ]);

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

        $imageFilenames = $this->fetchTable('ProductImages')
            ->find()
            ->select(['filename'])
            ->where(['product_id' => $id])
            ->all()
            ->map(fn($img) => $img->filename)
            ->toList();

        $this->fetchTable('OrderItems')->updateAll(['product_id' => null], ['product_id' => $id]);

        if ($this->Products->delete($product)) {
            Log::write('info', "Product deleted: id={$product->id}, name=\"{$product->name}\" by admin id={$adminId} ({$adminEmail})");
            $this->logActivity('Product', (int)$id, 'deleted', $product->name, [
                'name'           => $product->name,
                'sale_price'     => (string)$product->sale_price,
                'purchase_price' => (string)$product->purchase_price,
                'supplier_email' => $product->supplier_email,
                'description'    => $product->description,
                'story'          => $product->story,
                'category_id'    => $product->category_id,
                'featured'       => $product->featured,
                'images'         => $imageFilenames,
            ]);
            $this->Flash->success(__('The product has been deleted.'));
        } else {
            $this->Flash->error(__('The product could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function restoreProduct($logId = null)
    {
        $this->request->allowMethod(['post']);

        $identity = $this->Authentication->getIdentity();
        if (!$identity || $identity->get('role') !== 'admin') {
            $this->Flash->error(__('You do not have permission.'));
            return $this->redirect(['action' => 'index']);
        }

        $logsTable = $this->fetchTable('ActivityLogs');
        try {
            $log = $logsTable->get($logId);
        } catch (\Exception $e) {
            $this->Flash->error(__('Log entry not found.'));
            return $this->redirect(['action' => 'index']);
        }

        if ($log->model !== 'Product') {
            $this->Flash->error(__('Invalid log entry.'));
            return $this->redirect(['action' => 'index']);
        }

        $success = false;
        $productName = $log->model_label;

        if ($log->action === 'deleted') {
            $data = $log->changes ?? [];
            if (empty($data)) {
                $this->Flash->error(__('No restore data available for this entry.'));
                return $this->redirect(['action' => 'index']);
            }
            $imageFilenames = $data['images'] ?? [];
            unset($data['images']);
            $product = $this->Products->patchEntity($this->Products->newEmptyEntity(), $data);
            if ($this->Products->save($product)) {
                if (!empty($imageFilenames)) {
                    $productImagesTable = $this->fetchTable('ProductImages');
                    foreach ($imageFilenames as $filename) {
                        $img = $productImagesTable->newEmptyEntity();
                        $img->product_id = $product->id;
                        $img->filename   = $filename;
                        $productImagesTable->save($img);
                    }
                }
                $productName = $product->name;
                $success = true;
                $this->Flash->success(sprintf('"%s" has been restored.', $product->name));
            } else {
                $this->Flash->error(__('Could not restore the product.'));
            }
        } elseif ($log->action === 'updated') {
            try {
                $product = $this->Products->get($log->model_id);
            } catch (\Exception $e) {
                $this->Flash->error(__('Product no longer exists and cannot be reverted.'));
                return $this->redirect(['action' => 'index']);
            }
            $revertData = [];
            foreach ($log->changes ?? [] as $field => $change) {
                if (is_array($change) && array_key_exists('from', $change)) {
                    $revertData[$field] = $change['from'];
                }
            }
            if (empty($revertData)) {
                $this->Flash->error(__('Nothing to revert for this entry.'));
                return $this->redirect(['action' => 'index']);
            }
            $product = $this->Products->patchEntity($product, $revertData);
            if ($this->Products->save($product)) {
                $productName = $product->name;
                $success = true;
                $this->Flash->success(sprintf('"%s" has been reverted to its previous state.', $product->name));
            } else {
                $this->Flash->error(__('Could not revert the product.'));
            }
        }

        if ($success) {
            // Archive the original log entry
            $logsTable->updateAll(['is_archived' => true], ['id' => $log->id]);
            // Save a restored log entry and immediately archive it too
            $logsTable->save($logsTable->newEntity([
                'user_id'     => $identity->get('id'),
                'user_name'   => $identity->get('name') ?? $identity->get('email') ?? 'System',
                'action'      => 'restored',
                'model'       => 'Product',
                'model_id'    => $log->model_id,
                'model_label' => $productName,
                'changes'     => null,
                'is_archived' => true,
                'created'     => new \Cake\I18n\DateTime(),
            ]));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function archiveLog($logId = null)
    {
        $this->request->allowMethod(['post']);

        $identity = $this->Authentication->getIdentity();
        if (!$identity || $identity->get('role') !== 'admin') {
            $this->Flash->error(__('You do not have permission.'));
            return $this->redirect(['action' => 'index']);
        }

        $logsTable = $this->fetchTable('ActivityLogs');
        try {
            $log = $logsTable->get($logId);
        } catch (\Exception $e) {
            $this->Flash->error(__('Log entry not found.'));
            return $this->redirect(['action' => 'index']);
        }

        $logsTable->updateAll(['is_archived' => true], ['id' => $log->id]);

        return $this->redirect(['action' => 'index']);
    }
}
