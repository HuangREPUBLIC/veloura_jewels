<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */

$this->assign('title', $product->name);
$this->Html->css('jewelry', ['block' => true]);

$totalStock = 0;
$hasStock = false;
if (!empty($product->product_variants)) {
    foreach ($product->product_variants as $v) {
        $totalStock += $v->stock;
    }
    $hasStock = $totalStock > 0;
}
?>

<div class="jewelry-page">
    <?= $this->Html->link(__('← Back'), ['action' => 'index'], ['class' => 'jewelry-back-link']) ?>
    <div class="product-detail">
        <div class="product-detail-image">
            <?php if (!empty($product->product_images)): ?>
                <img
                    src="<?= $this->Url->image('products/' . h($product->product_images[0]->filename)) ?>"
                    alt="<?= h($product->name) ?>"
                    class="detail-image"
                >
            <?php else: ?>
                <div class="detail-placeholder"><span>No Image</span></div>
            <?php endif; ?>
        </div>

        <div class="product-detail-info">
            <p class="detail-label">Veloura Jewels</p>
            <h1 class="detail-name"><?= h($product->name) ?></h1>
            <p class="detail-price">$<?= number_format((float)$product->sale_price, 2) ?></p>

            <p class="detail-stock">
                <?php if ($totalStock <= 0): ?>
                    <span style="color:#dc2626;">Out of Stock</span>
                <?php elseif ($totalStock < 5): ?>
                    <span style="color:#b7860b;">Low Stock</span>
                <?php else: ?>
                    <span style="color:#16a34a;">In Stock</span>
                <?php endif; ?>
            </p>

            <div class="detail-description">
                <h3>Product Details</h3>
                <p><?= nl2br(h($product->description)) ?></p>
            </div>

            <?php if ($hasStock): ?>
                <?= $this->Form->create(null, [
                    'url'   => ['controller' => 'Jewelry', 'action' => 'addToCart'],
                    'class' => 'add-to-cart-form'
                ]) ?>
                <?= $this->Form->hidden('product_id', ['value' => $product->id]) ?>

                <div class="quantity-group">
                    <label class="quantity-label">Size</label>
                    <div class="qty-box">
                        <select name="variant_id" id="variant-select">
                            <option value="">-- Select --</option>
                            <?php foreach ($product->product_variants as $v): ?>
                                <?php if ($v->stock > 0): ?>
                                    <option value="<?= $v->id ?>" data-stock="<?= $v->stock ?>">
                                        <?= h($v->size) ?>
                                    </option>
                                <?php else: ?>
                                    <option value="" disabled><?= h($v->size) ?> (Out of Stock)</option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="quantity-group">
                    <label class="quantity-label" for="quantity">Quantity</label>
                    <div class="qty-box">
                        <button type="button" class="jewelry-qty-minus">−</button>
                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            min="1"
                            max="1"
                            value="1"
                            class="jewelry-quantity-input"
                        >
                        <button type="button" class="jewelry-qty-plus">+</button>
                    </div>
                </div>

                <div class="detail-actions">
                    <?= $this->Form->button('Add to Cart', [
                        'class' => 'jewelry-add-to-cart-btn',
                        'id'    => 'add-to-cart-btn',
                        'disabled' => true
                    ]) ?>
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
        var select  = document.getElementById('variant-select');
        var qtyInput = document.getElementById('quantity');
        var addBtn  = document.getElementById('add-to-cart-btn');

        if (select) {
            select.addEventListener('change', function () {
                var selected = this.options[this.selectedIndex];
                var stock = parseInt(selected.getAttribute('data-stock') || 0, 10);
                if (stock > 0) {
                    qtyInput.max = stock;
                    qtyInput.value = 1;
                    addBtn.disabled = false;
                } else {
                    qtyInput.max = 1;
                    addBtn.disabled = true;
                }
            });
        }

        document.querySelectorAll('.qty-box').forEach(function (wrapper) {
            var input = wrapper.querySelector('input[type="number"]');
            if (!input) return;
            var minus = wrapper.querySelector('.jewelry-qty-minus');
            var plus  = wrapper.querySelector('.jewelry-qty-plus');
            if (!minus || !plus) return;

            minus.addEventListener('click', function () {
                var value = parseInt(input.value || 1, 10);
                if (value > 1) input.value = value - 1;
            });

            plus.addEventListener('click', function () {
                var value = parseInt(input.value || 1, 10);
                var max   = parseInt(input.max || 999, 10);
                if (value < max) input.value = value + 1;
            });
        });
    });
</script>
