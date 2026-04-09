<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 * @var string[]|\Cake\Collection\CollectionInterface $categories
 */
?>
<div class="login-page">
    <div class="users form content">
        <?= $this->Html->link(__('← Back'), ['action' => 'index']) ?>

        <?= $this->Form->create($product) ?>

        <fieldset>
            <legend><?= __('Edit Product') ?></legend>

            <?= $this->Flash->render() ?>

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

            echo $this->Form->control('description', [
                'type' => 'textarea',
                'label' => 'Description',
                'required' => false
            ]);
            ?>
        </fieldset>

        <?= $this->Form->button(__('Save'), ['class' => 'login-button']) ?>
        <?= $this->Form->end() ?>


    </div>
</div>
