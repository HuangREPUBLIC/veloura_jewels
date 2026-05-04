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
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'view', $product->id]) ?>" class="product-card-link">
                    <div class="product-card">
                        <div class="product-image-wrapper<?= !empty($product->product_images[1]) ? ' has-hover-image' : '' ?>">
                            <?php if (!empty($product->featured)): ?>
                                <div class="product-card-badges">
                                    <span class="product-badge product-badge--featured">Featured</span>
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
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
