<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Wishlist> $wishlistItems
 */
$this->assign('title', 'My Wishlist');
$this->Html->css(['profile', 'jewelry'], ['block' => true]);
?>

<div class="profile-page wishlist-page">

    <?php $items = $wishlistItems->toArray(); ?>

    <?= $this->Html->link('&larr; Back to Profile', ['action' => 'index'], ['class' => 'wishlist-back-link', 'escape' => false]) ?>

    <div class="wishlist-hero">
        <h1 class="wishlist-title">Wishlist</h1>
        <?php if (!empty($items)): ?>
        <div class="wishlist-actions-row">
            <form method="post" action="<?= $this->Url->build(['action' => 'addWishlistToCart']) ?>" style="display:contents">
                <input type="hidden" name="_csrfToken" value="<?= h($this->request->getAttribute('csrfToken')) ?>">
                <button type="submit" class="wishlist-share-btn">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 01-8 0"/>
                    </svg>
                    Add Wishlist to Cart
                </button>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <?php if (empty($items)): ?>
        <div class="profile-card">
            <p class="profile-info-value">You have no saved items yet. Browse <?= $this->Html->link('Jewelry', '/jewelry') ?> or <?= $this->Html->link('Home Decor', '/home-decor') ?> to add some.</p>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($items as $item): ?>
                <?php
                $product       = $item->product;
                $isHomeDecor   = ($product->category->type ?? '') === 'home_decor';
                $productUrl    = $isHomeDecor
                    ? $this->Url->build('/home-decor/view/' . $product->id) . '?back=/profile/wishlist'
                    : $this->Url->build('/jewelry/view/' . $product->id) . '?back=/profile/wishlist';
                $hasHover   = !empty($product->product_images[1]);
                $variants   = $product->product_variants ?? [];
                $isOneSize  = count($variants) === 1;
                $oneVariant = $isOneSize ? $variants[0] : null;
                $hasStock   = false;
                foreach ($variants as $v) { if ($v->stock > 0) { $hasStock = true; break; } }
                $addToCartUrl = $this->Url->build(['controller' => 'Jewelry', 'action' => 'addToCart']);
                $csrfToken    = h($this->request->getAttribute('csrfToken'));
                ?>
                <div class="product-card-wrap" data-product-id="<?= $product->id ?>" style="position:relative">
                    <a href="<?= $productUrl ?>" class="product-card-link">
                        <div class="product-card">
                            <div class="product-image-wrapper<?= $hasHover ? ' has-hover-image' : '' ?>">
                                <?php if (!empty($product->product_images)): ?>
                                    <img src="<?= $this->Url->image('products/' . h($product->product_images[0]->filename)) ?>"
                                         alt="<?= h($product->name) ?>"
                                         class="product-image product-image--primary">
                                    <?php if ($hasHover): ?>
                                        <img src="<?= $this->Url->image('products/' . h($product->product_images[1]->filename)) ?>"
                                             alt="<?= h($product->name) ?>"
                                             class="product-image product-image--hover">
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="product-placeholder"><span>No Image</span></div>
                                <?php endif; ?>
                            </div>
                            <div class="product-card-body">
                                <h3 class="product-name"><?= h($product->name) ?></h3>
                                <p class="product-price">$<?= number_format((float)$product->sale_price, 2) ?></p>
                            </div>
                        </div>
                    </a>
                    <button class="wishlist-remove-btn"
                            data-product-id="<?= $product->id ?>"
                            type="button"
                            title="Remove from wishlist">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                    <form method="post" action="<?= $addToCartUrl ?>" class="wishlist-atb-form">
                        <input type="hidden" name="_csrfToken" value="<?= $csrfToken ?>">
                        <input type="hidden" name="product_id" value="<?= $product->id ?>">
                        <input type="hidden" name="quantity" value="1">
                        <?php if ($isOneSize && $oneVariant->stock > 0): ?>
                            <input type="hidden" name="variant_id" value="<?= $oneVariant->id ?>">
                        <?php endif; ?>
                        <?php if (!$isOneSize): ?>
                        <div class="wishlist-size-row">
                            <select name="variant_id" class="wishlist-size-select"
                                    data-atb-btn="wl-atb-<?= $product->id ?>">
                                <option value="">Select Size</option>
                                <?php foreach ($variants as $v): ?>
                                    <?php if ($v->stock > 0): ?>
                                        <option value="<?= $v->id ?>"><?= h($v->size) ?></option>
                                    <?php else: ?>
                                        <option value="" disabled><?= h($v->size) ?> (Out of Stock)</option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                        <?php if ($isOneSize && $oneVariant->stock > 0): ?>
                            <button type="submit" class="wishlist-add-to-bag-btn">Add to Bag</button>
                        <?php elseif (!$hasStock): ?>
                            <button type="button" class="wishlist-add-to-bag-btn wishlist-add-to-bag-btn--sold-out" disabled>Sold Out</button>
                        <?php else: ?>
                            <button type="submit" class="wishlist-add-to-bag-btn" id="wl-atb-<?= $product->id ?>" disabled>Add to Bag</button>
                        <?php endif; ?>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<script>
(function () {
    var toggleUrl = '<?= $this->Url->build('/profile/wishlist/toggle/') ?>';
    var csrfToken = <?= json_encode($this->request->getAttribute('csrfToken')) ?>;
    var emptyStateHtml = '<div class="profile-card"><p class="profile-info-value">You have no saved items yet. Browse <a href="/jewelry">Jewellery</a> or <a href="/home-decor">Home Décor</a> to add some.</p></div>';

    document.querySelectorAll('.wishlist-size-select').forEach(function (sel) {
        sel.addEventListener('change', function () {
            var btn = document.getElementById(sel.dataset.atbBtn);
            if (btn) btn.disabled = !sel.value;
        });
    });

    document.querySelectorAll('.wishlist-remove-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var wrap = btn.closest('.product-card-wrap');
            btn.disabled = true;
            fetch(toggleUrl + btn.dataset.productId, {
                method: 'POST',
                headers: { 'X-CSRF-Token': csrfToken }
            })
                .then(function (r) {
                    if (!r.ok) throw new Error('Wishlist request failed');
                    return r.json();
                })
                .then(function () {
                    var grid = wrap.closest('.product-grid');
                    var actions = document.querySelector('.wishlist-actions-row');
                    wrap.remove();
                    if (grid && !grid.querySelector('.product-card-wrap')) {
                        grid.insertAdjacentHTML('afterend', emptyStateHtml);
                        grid.remove();
                        if (actions) actions.remove();
                    }
                })
                .catch(function () {
                    btn.disabled = false;
                    window.alert('Could not update your wishlist. Please try again.');
                });
        });
    });
}());
</script>
