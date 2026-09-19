<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 * @var string $categoriesJson
 * @var array $types
 */
$this->assign('crumbRecord', h($product->name));

echo $this->element('product_form', [
    'title' => $product->name,
    'currentType' => $product->category->type ?? $product->type ?? '',
    'existingVariants' => array_map(
        fn($v) => ['id' => (int)$v->id, 'size' => $v->size, 'stock' => (int)$v->stock],
        $product->product_variants,
    ),
]);
