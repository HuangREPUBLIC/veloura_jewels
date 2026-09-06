<?php
/**
 * Quick view contents — shown when a listing card's quick add needs a size.
 * Rendered by JewelryController::quickAdd() and dropped into the shell in
 * templates/element/quick_view_modal.php.
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */

$variants = $product->product_variants ?? [];
$inStock = array_values(array_filter($variants, fn($variant) => $variant->stock > 0));
$images = $product->product_images ?? [];
?>
<div class="quick-view-media">
    <?php if (!empty($images)): ?>
        <div class="quick-view-stack" id="quickViewStack">
            <?php foreach ($images as $i => $img): ?>
                <div class="quick-view-slide">
                    <img
                        src="<?= $this->Url->image('products/' . h($img->filename)) ?>"
                        alt="<?= h($product->name) ?>"
                        <?= $i > 0 ? 'loading="lazy"' : '' ?>
                    >
                </div>
            <?php endforeach; ?>
        </div>
        <?php if (count($images) > 1): ?>
            <div class="quick-view-dots" id="quickViewDots">
                <?php foreach ($images as $i => $_img): ?>
                    <button type="button" class="quick-view-dot<?= $i === 0 ? ' active' : '' ?>"
                            data-index="<?= $i ?>" aria-label="Image <?= $i + 1 ?>"></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<div class="quick-view-detail">
    <p class="quick-view-brand">Veloura</p>
    <h2 class="quick-view-name"><?= h($product->name) ?></h2>
    <p class="quick-view-price">$<?= number_format((float)$product->sale_price, 2) ?></p>

    <?= $this->Form->create(null, [
        'url'        => ['controller' => 'Jewelry', 'action' => 'addToCart'],
        'class'      => 'quick-view-form',
        'novalidate' => true,
    ]) ?>
    <?= $this->Form->hidden('product_id', ['value' => $product->id]) ?>
    <?= $this->Form->hidden('quantity', ['value' => 1]) ?>

    <div class="quick-view-row">
        <label class="quick-view-label" for="quick-view-size">Size</label>
        <div class="quick-view-select-wrap">
            <select name="variant_id" id="quick-view-size" class="quick-view-select">
                <?php foreach ($inStock as $variant): ?>
                    <option value="<?= $variant->id ?>"><?= h($variant->size) ?></option>
                <?php endforeach; ?>
            </select>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </div>
    </div>

    <button type="submit" class="quick-view-add">Add to Cart</button>
    <?= $this->Form->end() ?>

    <a class="quick-view-more" href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'view', $product->id]) ?>">
        View full details
    </a>
</div>
