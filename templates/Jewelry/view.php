<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 * @var string $section 'jewelry' or 'home_decor'
 */

$this->assign('title', $product->name);
$this->Html->css('jewelry', ['block' => true]);

$totalStock = 0;
$hasStock = false;
$inStockVariants = [];
if (!empty($product->product_variants)) {
    foreach ($product->product_variants as $v) {
        $totalStock += $v->stock;
        if ($v->stock > 0) {
            $inStockVariants[] = $v;
        }
    }
    $hasStock = $totalStock > 0;
}
$isOneSizeOnly = !empty($inStockVariants)
    && count(array_unique(array_map(fn($v) => $v->size, $inStockVariants))) === 1
    && $inStockVariants[0]->size === 'One Size';
$oneSizeVariant = $isOneSizeOnly ? $inStockVariants[0] : null;
?>

<div class="jewelry-page">
    <?php
    $back    = $this->request->getQuery('back');
    $backUrl = ($back && str_starts_with($back, '/'))
        ? $back
        : ($section === 'home_decor' ? '/home-decor' : '/jewelry');
    ?>
    <?= $this->Html->link(__('← Back'), $backUrl, ['class' => 'jewelry-back-link']) ?>
    <div class="product-detail">
        <div class="product-detail-image">
            <?php if (!empty($product->product_images)): ?>
                <div class="detail-gallery">
                    <?php if (count($product->product_images) > 1): ?>
                        <div class="detail-thumbs">
                            <?php foreach ($product->product_images as $i => $img): ?>
                                <button
                                    type="button"
                                    class="detail-thumb <?= $i === 0 ? 'active' : '' ?>"
                                    data-src="<?= $this->Url->image('products/' . h($img->filename)) ?>"
                                >
                                    <img src="<?= $this->Url->image('products/' . h($img->filename)) ?>" alt="">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="detail-main-wrap">
                        <img
                            id="detail-main-img"
                            src="<?= $this->Url->image('products/' . h($product->product_images[0]->filename)) ?>"
                            alt="<?= h($product->name) ?>"
                            class="detail-image"
                        >
                    </div>
                </div>
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
                    <span class="stock-pill stock-pill--out">Out of Stock</span>
                <?php elseif ($totalStock < 5): ?>
                    <span class="stock-pill stock-pill--low">Low Stock</span>
                <?php else: ?>
                    <span class="stock-pill stock-pill--in">In Stock</span>
                <?php endif; ?>
            </p>

            <div class="detail-description">
                <h3>Product Details</h3>
                <p><?= nl2br(h($product->description)) ?></p>
            </div>

            <div class="detail-description">
                <h3>Story</h3>
                <p><?= nl2br(h($product->story)) ?></p>
            </div>

            <?php if ($hasStock): ?>
                <?= $this->Form->create(null, [
                    'url'        => ['controller' => 'Jewelry', 'action' => 'addToCart'],
                    'class'      => 'add-to-cart-form',
                    'novalidate' => true,
                ]) ?>
                <?= $this->Form->hidden('product_id', ['value' => $product->id]) ?>

                <div class="quantity-group">
                    <label class="quantity-label">Size</label>
                    <div class="qty-box">
                        <?php if ($isOneSizeOnly): ?>
                            <?= $this->Form->hidden('variant_id', ['value' => $oneSizeVariant->id]) ?>
                            <span class="one-size-pill"><?= h($oneSizeVariant->size) ?></span>
                        <?php else: ?>
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
                        <?php endif; ?>
                    </div>
                </div>

                <div class="quantity-group">
                    <label class="quantity-label" for="quantity">Quantity</label>
                    <div class="qty-row">
                        <div class="qty-box">
                            <button type="button" class="jewelry-qty-minus">−</button>
                            <input
                                type="number"
                                id="quantity"
                                name="quantity"
                                min="1"
                                max="<?= $isOneSizeOnly ? $oneSizeVariant->stock : 1 ?>"
                                value="1"
                                class="jewelry-quantity-input"
                            >
                            <button type="button" class="jewelry-qty-plus">+</button>
                        </div>
                        <span id="qty-toast" class="qty-toast" role="alert" aria-live="assertive"></span>
                    </div>
                </div>

                <div class="detail-actions">
                    <?= $this->Form->button('Add to Cart', [
                        'class' => 'jewelry-add-to-cart-btn',
                        'id'    => 'add-to-cart-btn',
                        'disabled' => !$isOneSizeOnly
                    ]) ?>
                </div>

                <?= $this->Form->end() ?>
            <?php else: ?>
                <div class="detail-actions">
                    <button class="jewelry-add-to-cart-btn" disabled>Add to Cart</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var select   = document.getElementById('variant-select');
        var qtyInput = document.getElementById('quantity');
        var addBtn   = document.getElementById('add-to-cart-btn');
        var toast    = document.getElementById('qty-toast');
        var toastTimer;

        function showQtyToast(msg) {
            clearTimeout(toastTimer);
            toast.textContent = msg;
            toast.classList.add('qty-toast--show');
            toastTimer = setTimeout(function () {
                toast.classList.remove('qty-toast--show');
            }, 2500);
        }

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

        if (qtyInput) {
            qtyInput.addEventListener('input', function () {
                var value = parseInt(this.value, 10);
                var max   = parseInt(this.max || 999, 10);
                if (isNaN(value) || value < 1) {
                    this.value = 1;
                } else if (value > max) {
                    this.value = max;
                    showQtyToast('Only ' + max + ' item' + (max === 1 ? '' : 's') + ' available');
                }
            });
        }

        var mainImg = document.getElementById('detail-main-img');
        document.querySelectorAll('.detail-thumb').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.detail-thumb').forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');
                mainImg.classList.add('fading');
                setTimeout(function () {
                    mainImg.src = btn.getAttribute('data-src');
                    mainImg.classList.remove('fading');
                }, 220);
            });
        });

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
                if (value < max) {
                    input.value = value + 1;
                } else {
                    showQtyToast('Only ' + max + ' item' + (max === 1 ? '' : 's') + ' available');
                }
            });
        });
    });
</script>
