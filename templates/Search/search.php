<?php
$this->assign('title', 'Search');
$this->Html->css('jewelry', ['block' => true]);
?>

<div class="jewelry-page">
    <div class="search-results-bar">
        <h1 class="search-results-title">Search</h1>
        <?php if ($q !== ''): ?>
            <p class="search-results-count">
                <?= count($products) ?> result<?= count($products) !== 1 ? 's' : '' ?> for &ldquo;<?= h($q) ?>&rdquo;
            </p>
        <?php endif; ?>
    </div>

    <?php if ($q === ''): ?>
        <div class="empty-state">
            <p>Enter a keyword above to search our collection.</p>
        </div>

    <?php elseif (empty($products) || (is_countable($products) && count($products) === 0)): ?>
        <div class="empty-state">
            <p>No results found for <strong>"<?= h($q) ?>"</strong>.</p>
            <p>
                <?= $this->Html->link('Browse Jewelry', ['controller' => 'Jewelry', 'action' => 'index']) ?>
                &nbsp;or&nbsp;
                <?= $this->Html->link('Browse Home Décor', ['controller' => 'Jewelry', 'action' => 'home_decor']) ?>
            </p>
        </div>

    <?php else: ?>
        <?php
        $identity    = $this->request->getAttribute('identity');
        $wishlistIds = $wishlistIds ?? [];
        $searchBack  = rawurlencode($this->request->getPath() . ($this->request->getUri()->getQuery() ? '?' . $this->request->getUri()->getQuery() : ''));
        ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <?php
                $isHomeDecor = ($product->category->type ?? '') === 'home_decor';
                $productUrl = $isHomeDecor
                    ? $this->Url->build('/home-decor/view/' . $product->id)
                    : $this->Url->build('/jewelry/view/' . $product->id);
                ?>
                <div class="product-card-wrap">
                <a href="<?= $productUrl ?>?back=<?= $searchBack ?>" class="product-card-link">
                    <div class="product-card">
                        <div class="product-image-wrapper<?= !empty($product->product_images[1]) ? ' has-hover-image' : '' ?>">
                            <?php if (!empty($product->featured) || !empty($product->is_bestsales)): ?>
                                <div class="product-card-badges">
                                    <?php if (!empty($product->featured)): ?>
                                        <span class="product-badge product-badge--featured">Featured</span>
                                    <?php endif; ?>

                                    <?php if (!empty($product->is_bestsales)): ?>
                                        <span class="product-badge product-badge--bestsales">Best Seller</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($product->product_images)): ?>
                                <img
                                    src="<?= $this->Url->image('products/' . h($product->product_images[0]->filename)) ?>"
                                    alt="<?= h($product->name) ?>"
                                    class="product-image product-image--primary"
                                >
                                <?php if (!empty($product->product_images[1])): ?>
                                    <img
                                        src="<?= $this->Url->image('products/' . h($product->product_images[1]->filename)) ?>"
                                        alt="<?= h($product->name) ?>"
                                        class="product-image product-image--hover"
                                    >
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="product-placeholder">
                                    <span>No Image</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="product-card-body">
                            <h3 class="product-name"><?= h($product->name) ?></h3>
                            <p class="product-price">$<?= number_format((float)$product->sale_price, 2) ?></p>
                        </div>
                    </div>
                </a>
                <button class="wishlist-btn<?= in_array($product->id, $wishlistIds) ? ' wishlisted' : '' ?>"
                        data-product-id="<?= $product->id ?>"
                        type="button"
                        aria-label="Save to wishlist">
                    <?= $this->element('wishlist_heart') ?>
                </button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
