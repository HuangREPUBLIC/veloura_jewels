<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $products
 * @var iterable<\App\Model\Entity\Category> $categories
 * @var int $categoryId
 * @var string|null $minPrice
 * @var string|null $maxPrice
 */

$this->assign('title', 'Jewelry');
$this->Html->css('jewelry', ['block' => true]);
?>

<div class="jewelry-page">
    <section class="jewelry-hero">
        <h1>Our Jewelry Collection</h1>
        <p>Discover timeless pieces crafted to elevate every occasion.</p>
    </section>

    <!-- Filter Bar -->
    <div class="jewelry-filter-bar">
        <?= $this->Form->create(null, [
            'type' => 'get',
            'url' => ['controller' => 'Jewelry', 'action' => 'index'],
            'class' => 'jewelry-filter-form'
        ]) ?>

        <!-- Category Filter -->
        <div class="filter-group">
            <label class="filter-label">Category</label>
            <div class="filter-options">
                <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'index']) ?>"
                   class="filter-tag <?= $categoryId === 0 ? 'active' : '' ?>">
                    All
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a href="<?= $this->Url->build([
                        'controller' => 'Jewelry',
                        'action' => 'index',
                        '?' => array_filter([
                            'category' => $cat->id,
                            'min_price' => $minPrice,
                            'max_price' => $maxPrice,
                        ])
                    ]) ?>"
                       class="filter-tag <?= $categoryId === $cat->id ? 'active' : '' ?>">
                        <?= h($cat->name) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Price Filter -->
        <div class="filter-group filter-group-price">
            <label class="filter-label">Price Range</label>
            <div class="filter-price-inputs">
                <?= $this->Form->hidden('category', ['value' => $categoryId ?: '']) ?>
                <span class="price-prefix">$</span>
                <?= $this->Form->number('min_price', [
                    'placeholder' => 'Min',
                    'value' => $minPrice,
                    'class' => 'price-input',
                    'min' => 0,
                    'step' => 1,
                ]) ?>
                <span class="price-sep">—</span>
                <span class="price-prefix">$</span>
                <?= $this->Form->number('max_price', [
                    'placeholder' => 'Max',
                    'value' => $maxPrice,
                    'class' => 'price-input',
                    'min' => 0,
                    'step' => 1,
                ]) ?>
                <button type="submit" class="filter-apply-btn">Apply</button>
                <?php if ($minPrice || $maxPrice || $categoryId): ?>
                    <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'index']) ?>"
                       class="filter-clear-btn">Clear</a>
                <?php endif; ?>
            </div>
        </div>

        <?= $this->Form->end() ?>
    </div>

    <?php if ($products->isEmpty()): ?>
        <div class="empty-state">
            <p>No products found. <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'index']) ?>">Clear filters</a></p>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <a href="<?= $this->Url->build('/jewelry/view/' . $product->id) ?>" class="product-card-link">
                    <div class="product-card">
                        <div class="product-image-wrapper">
                            <?php if (!empty($product->product_images)): ?>
                                <img
                                    src="<?= $this->Url->image(h($product->product_images[0]->filename)) ?>"
                                    alt="<?= h($product->name) ?>"
                                    class="product-image"
                                >
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
