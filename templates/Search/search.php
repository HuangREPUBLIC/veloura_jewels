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
                <div class="product-card-wrap">
                <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'view', $product->id]) ?>?back=<?= $searchBack ?>" class="product-card-link">
                    <div class="product-card">
                        <div class="product-image-wrapper<?= !empty($product->product_images[1]) ? ' has-hover-image' : '' ?>">
                            <?php if (!empty($product->featured) || !empty($product->is_bestsales)): ?>
                                <div class="product-card-badges">
                                    <?php if (!empty($product->featured)): ?>
                                        <span class="product-badge product-badge--featured">Featured</span>
                                    <?php endif; ?>

                                    <?php if (!empty($product->is_bestsales)): ?>
                                        <span class="product-badge product-badge--bestsales">Best Sales</span>
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
                <?php if ($identity): ?>
                <button class="wishlist-btn<?= in_array($product->id, $wishlistIds) ? ' wishlisted' : '' ?>"
                        data-product-id="<?= $product->id ?>"
                        type="button"
                        aria-label="Save to wishlist">
                    <svg width="20" height="20" viewBox="0 0 64 64" fill="currentColor">
                        <path d="M32,57C31,56.5 5,42 5,23.5C5,13.8 12.2,6.5 21,6.5C26,6.5 30.4,9 32,11.2C33.6,9 38,6.5 43,6.5C51.8,6.5 59,13.8 59,23.5C59,42 33,56.5 32,57Z"/>
                    </svg>
                </button>
                <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
