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
    <?= $this->Html->link('← Back', $backUrl, ['class' => 'jewelry-back-link']) ?>

    <div class="product-detail">

        <div class="product-detail-image">
            <?php if (!empty($product->product_images)): ?>
                <div class="detail-image-stack">
                    <?php foreach ($product->product_images as $i => $img): ?>
                        <div class="detail-stack-item">
                            <img
                                src="<?= $this->Url->image('products/' . h($img->filename)) ?>"
                                alt="<?= h($product->name) ?>"
                                class="detail-stack-img"
                                <?= $i > 0 ? 'loading="lazy"' : '' ?>
                            >
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="detail-placeholder"><span>No Image</span></div>
            <?php endif; ?>
        </div>

        <div class="product-detail-info">

            <?php if (!empty($product->featured) || !empty($product->is_bestsales)): ?>
                <div class="detail-badges">
                    <?php if (!empty($product->featured)): ?>
                        <span class="product-badge product-badge--featured">Featured</span>
                    <?php endif; ?>
                    <?php if (!empty($product->is_bestsales)): ?>
                        <span class="product-badge product-badge--bestsales">Best Sales</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <h1 class="detail-name"><?= h($product->name) ?></h1>

            <div class="detail-price-row">
                <span class="detail-price">$<?= number_format((float)$product->sale_price, 2) ?></span>
                <?php if ($totalStock <= 0): ?>
                    <span class="stock-pill stock-pill--out">Out of Stock</span>
                <?php elseif ($totalStock < 5): ?>
                    <span class="stock-pill stock-pill--low">Low Stock</span>
                <?php else: ?>
                    <span class="stock-pill stock-pill--in">In Stock</span>
                <?php endif; ?>
            </div>

            <?php if ($hasStock): ?>
                <?= $this->Form->create(null, [
                    'url'        => ['controller' => 'Jewelry', 'action' => 'addToCart'],
                    'class'      => 'add-to-cart-form',
                    'novalidate' => true,
                ]) ?>
                <?= $this->Form->hidden('product_id', ['value' => $product->id]) ?>
                <?= $this->Form->hidden('variant_id', ['id' => 'variant-id-input', 'value' => $isOneSizeOnly ? $oneSizeVariant->id : '']) ?>

                <div class="detail-section">
                    <div class="detail-section-label">
                        Size<?php if (!$isOneSizeOnly): ?> <span id="selected-size-name" class="selected-size-name"></span><?php endif; ?>
                    </div>
                    <?php if ($isOneSizeOnly): ?>
                        <span class="one-size-pill"><?= h($oneSizeVariant->size) ?></span>
                    <?php else: ?>
                        <div class="size-pills">
                            <?php foreach ($product->product_variants as $v): ?>
                                <?php if ($v->stock > 0): ?>
                                    <button type="button" class="size-pill"
                                        data-id="<?= $v->id ?>"
                                        data-stock="<?= $v->stock ?>"
                                        data-name="<?= h($v->size) ?>">
                                        <?= h($v->size) ?>
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="size-pill size-pill--out" disabled>
                                        <?= h($v->size) ?>
                                    </button>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="detail-section">
                    <div class="detail-section-label">Quantity</div>
                    <div class="qty-stepper">
                        <button type="button" class="qty-stepper-btn" id="qty-minus"<?= !$isOneSizeOnly ? ' disabled' : '' ?>>−</button>
                        <input type="number" name="quantity" id="quantity"
                            value="1" min="1"
                            max="<?= $isOneSizeOnly ? $oneSizeVariant->stock : 99 ?>"
                            class="qty-stepper-input">
                        <button type="button" class="qty-stepper-btn" id="qty-plus"<?= !$isOneSizeOnly ? ' disabled' : '' ?>>+</button>
                    </div>
                    <span id="qty-toast" class="qty-toast" role="alert" aria-live="assertive"></span>
                </div>

                <div class="detail-add-row">
                    <?= $this->Form->button('Add to Cart', [
                        'class'    => 'jewelry-add-to-cart-btn',
                        'id'       => 'add-to-cart-btn',
                        'disabled' => !$isOneSizeOnly,
                    ]) ?>
                </div>

                <?= $this->Form->end() ?>
            <?php else: ?>
                <div class="detail-add-row" style="margin-top:1.8rem">
                    <button class="jewelry-add-to-cart-btn" disabled>Out of Stock</button>
                </div>
            <?php endif; ?>

            <?php if (!empty($product->description)): ?>
                <details class="detail-accordion" open>
                    <summary class="detail-accordion-summary">Product Details</summary>
                    <div class="detail-accordion-body">
                        <p><?= nl2br(h($product->description)) ?></p>
                    </div>
                </details>
            <?php endif; ?>

            <?php if (!empty($product->story)): ?>
                <details class="detail-accordion">
                    <summary class="detail-accordion-summary">The Story</summary>
                    <div class="detail-accordion-body">
                        <p><?= nl2br(h($product->story)) ?></p>
                    </div>
                </details>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var variantInput = document.getElementById('variant-id-input');
        var qtyInput     = document.getElementById('quantity');
        var addBtn       = document.getElementById('add-to-cart-btn');
        var minusBtn     = document.getElementById('qty-minus');
        var plusBtn      = document.getElementById('qty-plus');
        var toast        = document.getElementById('qty-toast');
        var toastTimer;

        function showToast(msg) {
            clearTimeout(toastTimer);
            toast.textContent = msg;
            toast.classList.add('qty-toast--show');
            toastTimer = setTimeout(function () { toast.classList.remove('qty-toast--show'); }, 2500);
        }

        function getMax() { return parseInt(qtyInput.max || 99, 10); }
        function getVal() { return parseInt(qtyInput.value || 1, 10); }

        if (minusBtn) {
            minusBtn.addEventListener('click', function () {
                var v = getVal();
                if (v > 1) qtyInput.value = v - 1;
            });
        }

        if (plusBtn) {
            plusBtn.addEventListener('click', function () {
                var v = getVal(), m = getMax();
                if (v < m) { qtyInput.value = v + 1; }
                else { showToast('Only ' + m + ' item' + (m === 1 ? '' : 's') + ' available'); }
            });
        }

        if (qtyInput) {
            qtyInput.addEventListener('input', function () {
                var v = parseInt(this.value, 10), m = getMax();
                if (isNaN(v) || v < 1) { this.value = 1; }
                else if (v > m) { this.value = m; showToast('Only ' + m + ' item' + (m === 1 ? '' : 's') + ' available'); }
            });
        }

        // Size pill selection
        document.querySelectorAll('.size-pill:not(.size-pill--out)').forEach(function (pill) {
            pill.addEventListener('click', function () {
                document.querySelectorAll('.size-pill').forEach(function (p) { p.classList.remove('size-pill--selected'); });
                this.classList.add('size-pill--selected');

                var id    = this.dataset.id;
                var stock = parseInt(this.dataset.stock, 10);
                var name  = this.dataset.name;

                variantInput.value = id;
                qtyInput.max       = stock;
                qtyInput.value     = 1;

                var label = document.getElementById('selected-size-name');
                if (label) label.textContent = '— ' + name;

                if (addBtn) addBtn.disabled = false;
                if (minusBtn) minusBtn.disabled = false;
                if (plusBtn) plusBtn.disabled = false;
            });
        });
    });
</script>
