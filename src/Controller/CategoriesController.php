<?php
declare(strict_types=1);

namespace App\Controller;

class CategoriesController extends AppController
{
    private function jsonResponse(array $data, int $status = 200): \Cake\Http\Response
    {
        return $this->response
            ->withStatus($status)
            ->withType('application/json')
            ->withStringBody((string)json_encode($data));
    }

    private function isAdmin(): bool
    {
        $identity = $this->Authentication->getIdentity();
        return $identity && $identity->get('role') === 'admin';
    }

    public function deleteCategory($id = null): \Cake\Http\Response
    {
        $this->request->allowMethod(['post']);

        if (!$this->isAdmin()) {
            return $this->jsonResponse(['error' => 'Forbidden'], 403);
        }

        $categoriesTable = $this->fetchTable('Categories');

        try {
            $cat = $categoriesTable->get((int)$id);
        } catch (\Exception $e) {
            return $this->jsonResponse(['error' => 'Category not found.'], 404);
        }

        $inUse = $this->fetchTable('Products')->find()->where(['category_id' => $cat->id])->count();
        if ($inUse > 0) {
            return $this->jsonResponse(['error' => "Cannot delete — {$inUse} product(s) still use this category."], 422);
        }

        if ($categoriesTable->delete($cat)) {
            return $this->jsonResponse(['success' => true]);
        }

        return $this->jsonResponse(['error' => 'Could not delete category.'], 500);
    }

    public function removeSize($id = null): \Cake\Http\Response
    {
        $this->request->allowMethod(['post']);

        if (!$this->isAdmin()) {
            return $this->jsonResponse(['error' => 'Forbidden'], 403);
        }

        $size = trim((string)($this->request->getData('size') ?? ''));
        if ($size === '') {
            return $this->jsonResponse(['error' => 'Size name is required.'], 422);
        }

        $categoriesTable = $this->fetchTable('Categories');

        try {
            $cat = $categoriesTable->get((int)$id);
        } catch (\Exception $e) {
            return $this->jsonResponse(['error' => 'Category not found.'], 404);
        }

        $variantsTable = $this->fetchTable('ProductVariants');
        $variants = $variantsTable->find()
            ->matching('Products', fn($q) => $q->where(['Products.category_id' => $cat->id]))
            ->where(['ProductVariants.size' => $size])
            ->all();

        foreach ($variants as $variant) {
            $variantsTable->delete($variant);
        }

        $sizes = array_values(array_filter($cat->sizes ?? [], fn($s) => $s !== $size));
        $cat->sizes = $sizes;

        if ($categoriesTable->save($cat)) {
            return $this->jsonResponse(['success' => true, 'sizes' => $sizes]);
        }

        return $this->jsonResponse(['error' => 'Could not update sizes.'], 500);
    }
}
