<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $products
 * @var iterable<\App\Model\Entity\Category> $categories
 * @var int $categoryId
 * @var string|null $minPrice
 * @var string|null $maxPrice
 * @var string $sortBy
 */

$this->assign('title', 'Home Decor');
$this->Html->css('jewelry', ['block' => true]);
?>
<?php
// Build current query params for sort links (preserves category + price filters)
$currentParams = array_filter([
    'category'  => $categoryId ?: null,
    'min_price' => $minPrice,
    'max_price' => $maxPrice,
]);
?>

<div class="jewelry-page">
    <section class="jewelry-hero">
        <h1>Our Home Decor Collection</h1>
        <p>Discover timeless pieces crafted to elevate every occasion.</p>
    </section>

    <!-- Filter Bar -->
    <div class="jewelry-filter-bar">
        <?= $this->Form->create(null, [
            'type' => 'get',
            'url' => ['controller' => 'Jewelry', 'action' => 'home_decor'],
            'class' => 'jewelry-filter-form'
        ]) ?>

        <div class="filter-bar-inner">

            <!-- Category Dropdown -->
            <div class="filter-dropdown" id="filter-category">
                <button type="button" class="filter-dropdown-btn <?= $categoryId > 0 ? 'is-active' : '' ?>">
                    Category
                    <?php if ($categoryId > 0): ?>
                        <?php foreach ($categories as $cat): ?>
                            <?php if ($cat->id === $categoryId): ?>
                                <span class="filter-active-label">: <?= h($cat->name) ?></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <svg class="filter-chevron" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </button>
                <div class="filter-dropdown-menu">
                    <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'home_decor', '?' => array_filter(['min_price' => $minPrice, 'max_price' => $maxPrice, 'sort' => $sortBy !== 'newest' ? $sortBy : null])]) ?>"
                       class="filter-dropdown-item <?= $categoryId === 0 ? 'active' : '' ?>">All</a>
                    <?php foreach ($categories as $cat): ?>
                        <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'home_decor', '?' => array_filter(['category' => $cat->id, 'min_price' => $minPrice, 'max_price' => $maxPrice, 'sort' => $sortBy !== 'newest' ? $sortBy : null])]) ?>"
                           class="filter-dropdown-item <?= $categoryId === $cat->id ? 'active' : '' ?>">
                            <?= h($cat->name) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Price Dropdown -->
            <div class="filter-dropdown" id="filter-price">
                <button type="button" class="filter-dropdown-btn <?= ($minPrice || $maxPrice) ? 'is-active' : '' ?>">
                    Price
                    <?php if ($minPrice || $maxPrice): ?>
                        <span class="filter-active-label">: $<?= $minPrice ?: '0' ?> — $<?= $maxPrice ?: '∞' ?></span>
                    <?php endif; ?>
                    <svg class="filter-chevron" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </button>
                <div class="filter-dropdown-menu filter-dropdown-menu--price">
                    <?= $this->Form->hidden('category', ['value' => $categoryId ?: '']) ?>
                    <?= $this->Form->hidden('sort', ['value' => $sortBy]) ?>
                    <div class="price-dropdown-row">
                        <span class="price-prefix">$</span>
                        <?= $this->Form->number('min_price', ['placeholder' => 'Min', 'value' => $minPrice, 'class' => 'price-input', 'min' => 0, 'step' => 1]) ?>
                        <span class="price-sep">—</span>
                        <span class="price-prefix">$</span>
                        <?= $this->Form->number('max_price', ['placeholder' => 'Max', 'value' => $maxPrice, 'class' => 'price-input', 'min' => 0, 'step' => 1]) ?>
                    </div>
                    <button type="submit" class="filter-apply-btn">Apply</button>
                </div>
            </div>

            <!-- Sort Dropdown -->
            <div class="filter-dropdown filter-dropdown--right" id="filter-sort">
                <?php
                $sorts = ['newest' => 'Newest', 'price_asc' => 'Price: Low to High', 'price_desc' => 'Price: High to Low', 'name_asc' => 'Name: A–Z'];
                $sortLabel = $sorts[$sortBy] ?? 'Newest';
                ?>
                <button type="button" class="filter-dropdown-btn <?= $sortBy !== 'newest' ? 'is-active' : '' ?>">
                    Sort by: <span class="filter-active-label"><?= $sortLabel ?></span>
                    <svg class="filter-chevron" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </button>
                <div class="filter-dropdown-menu filter-dropdown-menu--sort">
                    <?php foreach ($sorts as $key => $label): ?>
                        <label class="filter-dropdown-item <?= $sortBy === $key ? 'active' : '' ?>">
                            <input type="radio" name="sort" value="<?= $key ?>"
                                <?= $sortBy === $key ? 'checked' : '' ?>
                                   onchange="this.form.submit()">
                            <?= $label ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Clear -->
            <?php if ($minPrice || $maxPrice || $categoryId || ($sortBy && $sortBy !== 'newest')): ?>
                <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'home_decor']) ?>"
                   class="filter-clear-btn">Clear</a>
            <?php endif; ?>

        </div>

        <?= $this->Form->end() ?>
    </div>

    <script>
        (function () {
            var dropdowns = document.querySelectorAll('.jewelry-page .filter-dropdown');

            dropdowns.forEach(function (dd) {
                var btn = dd.querySelector('.filter-dropdown-btn');
                if (!btn) return;
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var isOpen = dd.classList.contains('open');
                    dropdowns.forEach(function (other) { other.classList.remove('open'); });
                    if (!isOpen) dd.classList.add('open');
                });
            });

            document.addEventListener('click', function () {
                dropdowns.forEach(function (dd) { dd.classList.remove('open'); });
            });

            document.querySelectorAll('.jewelry-page .filter-dropdown-menu').forEach(function (menu) {
                menu.addEventListener('click', function (e) { e.stopPropagation(); });
            });
        })();
    </script>

    <?php if ($products->isEmpty()): ?>
        <div class="empty-state">
            <p>No products found. <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'home_decor']) ?>">Clear filters</a></p>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <a href="<?= $this->Url->build('/home-decor/view/' . $product->id) ?>" class="product-card-link">
                    <div class="product-card">
                        <div class="product-image-wrapper<?= !empty($product->product_images[1]) ? ' has-hover-image' : '' ?>">
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
