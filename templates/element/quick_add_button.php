<?php
/**
 * Quick add button shown over a product card on hover.
 * Sits next to the wishlist heart, outside the card's link.
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */

$variants = $product->product_variants;
$soldOut = $variants !== null && !array_filter($variants, fn($variant) => $variant->stock > 0);
?>
<?php if (!$soldOut): ?>
<button class="quick-add-btn"
        type="button"
        data-product-id="<?= $product->id ?>"
        data-product-url="<?= h($this->Url->build(['controller' => 'Jewelry', 'action' => 'view', $product->id])) ?>"
        aria-label="Add <?= h($product->name) ?> to cart">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
    </svg>
</button>
<?php endif; ?>
