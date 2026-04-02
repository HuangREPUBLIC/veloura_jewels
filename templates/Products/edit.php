<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 * @var string[]|\Cake\Collection\CollectionInterface $categories
 */
?>
<?php $this->Html->css('default-styles', ['block' => true]); ?>
<?php $this->Html->css('login', ['block' => true]); ?>

<div class="users form content">
    <h2><?= __('Edit Product') ?></h2>
    <?= $this->Html->link(__('← Back'), ['action' => 'index'], ['class' => 'action-buttons-inline']) ?>
    <br>

    <?= $this->Form->create($product) ?>
    <fieldset>
        <?php
        echo $this->Form->control('name', [
            'label' => 'Name',
            'required' => true
        ]);

        echo $this->Form->control('purchase_price', [
            'label' => 'Purchase Price',
            'required' => true
        ]);

        echo $this->Form->control('sale_price', [
            'label' => 'Sale Price',
            'required' => true
        ]);

        echo $this->Form->control('stock', [
            'label' => 'Stock',
            'required' => true
        ]);

        echo $this->Form->control('supplier_email', [
            'label' => 'Supplier Email'
        ]);

        echo $this->Form->control('categories._ids', [
            'type' => 'select',
            'options' => $categories,
            'empty' => 'Select a category',
            'label' => 'Categories'
        ]);
        echo $this->Form->control('product_images', [
            'type' => 'file',
            'label' => 'Images'
        ]);
        ?>
    </fieldset>

    <?= $this->Form->button(__('Save')) ?>
    <?= $this->Form->end() ?>
</div>
