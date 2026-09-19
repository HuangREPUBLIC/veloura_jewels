<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 * @var string $categoriesJson
 * @var array $types
 * @var string|null $preselectedType
 */
$this->assign('crumbRecord', 'Add Product');

echo $this->element('product_form', [
    'title' => 'Add Product',
    'currentType' => $preselectedType ?? '',
    'existingVariants' => [],
]);
