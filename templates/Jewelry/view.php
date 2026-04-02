<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */

$this->assign('title', $product->name);
echo $this->Html->css('jewelry');
?>

<div class="jewelry-page">
    <?= $this->Html->link(__('← Back'), ['action' => 'index'], ['class' => 'action-buttons-inline']) ?>
    <div class="product-detail">
        <div class="product-detail-image">
            <?php if (!empty($product->product_images)): ?>
                <img
                    src="<?= $this->Url->image(h($product->product_images[0]->filename)) ?>"
                    alt="<?= h($product->name) ?>"
                    class="detail-image"
                >
            <?php else: ?>
                <div class="detail-placeholder">
                    <span>No Image</span>
                </div>
            <?php endif; ?>
        </div>

        <div class="product-detail-info">
            <p class="detail-label">Veloura Jewels</p>
            <h1 class="detail-name"><?= h($product->name) ?></h1>
            <p class="detail-price">$<?= number_format((float)$product->sale_price, 2) ?></p>

            <p class="detail-stock">
                <?php if ((int)$product->stock > 0): ?>
                    In stock: <?= (int)$product->stock ?>
                <?php else: ?>
                    Out of stock
                <?php endif; ?>
            </p>

            <div class="detail-description">
                <h3>Product Details</h3>
                <p>
                    This piece is part of our curated jewelry collection, designed to bring
                    elegance and timeless style to your everyday wear.
                </p>
            </div>

            <?php if ((int)$product->stock > 0): ?>
                <?= $this->Form->create(null, [
                    'url' => ['controller' => 'Jewelry', 'action' => 'addToCart'],
                    'class' => 'add-to-cart-form'
                ]) ?>

                <?= $this->Form->hidden('product_id', ['value' => $product->id]) ?>

                <div class="quantity-group">
                    <?= $this->Form->label('quantity', 'Quantity', ['class' => 'quantity-label']) ?>
                    <?= $this->Form->control('quantity', [
                        'type' => 'number',
                        'min' => 1,
                        'max' => (int)$product->stock,
                        'value' => 1,
                        'label' => false,
                        'class' => 'quantity-input'
                    ]) ?>
                </div>

                <div class="detail-actions">
                    <?= $this->Form->button('Add to Cart', ['class' => 'btn-view']) ?>
                </div>

                <?= $this->Form->end() ?>
            <?php else: ?>
                <div class="detail-actions">
                    <?= $this->Html->link(
                        'Back to Jewelry',
                        ['controller' => 'Jewelry', 'action' => 'index'],
                        ['class' => 'btn-secondary']
                    ) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
