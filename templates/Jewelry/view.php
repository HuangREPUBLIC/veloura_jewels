<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */

$this->assign('title', $product->name);
echo $this->Html->css('jewelry');
?>

<div class="jewelry-page">
    <?= $this->Html->link(__('← Back'), ['action' => 'index'], ['class' => 'jewelry-back-link']) ?>
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
                    Stock: <?= (int)$product->stock ?>
                <?php else: ?>
                    Out of stock
                <?php endif; ?>
            </p>

            <div class="detail-description">
                <h3>Product Details</h3>
                <p><?= nl2br(h($product->description)) ?></p>
            </div>


            <?php if ((int)$product->stock > 0): ?>
                <?= $this->Form->create(null, [
                    'url' => ['controller' => 'Jewelry', 'action' => 'addToCart'],
                    'class' => 'add-to-cart-form'
                ]) ?>

                <?= $this->Form->hidden('product_id', ['value' => $product->id]) ?>

                <div class="quantity-group">
                    <?= $this->Form->label('quantity', 'Quantity', ['class' => 'quantity-label']) ?>

                    <div class="qty-box">
                        <button type="button" class="jewelry-qty-minus">−</button>

                        <?= $this->Form->number('quantity', [
                            'min' => 1,
                            'max' => (int)$product->stock,
                            'value' => 1,
                            'class' => 'jewelry-quantity-input',
                            'label' => false
                        ]) ?>

                        <button type="button" class="jewelry-qty-plus">+</button>
                    </div>
                </div>

                <div class="detail-actions">
                    <?= $this->Form->button('Add to Cart', ['class' => 'jewelry-add-to-cart-btn']) ?>
                </div>

                <?= $this->Form->end() ?>
            <?php else: ?>
                <div class="detail-actions">
                    <?= $this->Html->link(
                        'Back to Jewelry',
                        ['controller' => 'Jewelry', 'action' => 'index'],
                        ['class' => 'jewelry-btn-secondary']
                    ) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.qty-box').forEach(function (wrapper) {
            const input = wrapper.querySelector('input');
            const minus = wrapper.querySelector('.jewelry-qty-minus');
            const plus = wrapper.querySelector('.jewelry-qty-plus');

            minus.addEventListener('click', function () {
                let value = parseInt(input.value || 0, 10);
                if (value > 1) {
                    input.value = value - 1;
                }
            });

            plus.addEventListener('click', function () {
                let value = parseInt(input.value || 0, 10);
                const max = parseInt(input.max || 999999, 10);

                if (value < max) {
                    input.value = value + 1;
                }
            });
        });
    });
</script>
