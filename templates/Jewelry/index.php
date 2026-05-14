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

$this->assign('title', 'Jewelry');
$this->Html->css('jewelry', ['block' => true]);
?>

<div class="jewelry-page">
    <section class="jewelry-hero">
        <h1><?= h($pageContent['hero_heading'] ?? 'Our Jewellery Collection') ?></h1>
        <p><?= h($pageContent['hero_subtext'] ?? '') ?></p>
    </section>

    <!-- Filter Bar -->
    <?php
    // Materialize categories so we can iterate it more than once safely.
    $categoriesArr = is_array($categories) ? $categories : iterator_to_array($categories);
    $activeCategory = null;
    foreach ($categoriesArr as $cat) {
        if ((int)$cat->id === (int)$categoryId) {
            $activeCategory = $cat;
            break;
        }
    }
    ?>
    <div class="jewelry-filter-bar">
        <?= $this->Form->create(null, [
            'type' => 'get',
            'url' => ['controller' => 'Jewelry', 'action' => 'index'],
        ]) ?>

        <div class="filter-bar-inner">

            <!-- Category Dropdown -->
            <div class="filter-dropdown" id="filter-category">
                <button type="button" class="filter-dropdown-btn <?= $categoryId > 0 ? 'is-active' : '' ?>" aria-expanded="false" aria-controls="filter-category-menu">
                    Category
                    <?php if ($activeCategory): ?>
                        <span class="filter-active-label">: <?= h($activeCategory->name) ?></span>
                    <?php endif; ?>
                    <svg class="filter-chevron" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </button>
                <div class="filter-dropdown-menu" id="filter-category-menu">
                    <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'index', '?' => array_filter(['min_price' => $minPrice, 'max_price' => $maxPrice, 'sort' => $sortBy !== 'newest' ? $sortBy : null])]) ?>"
                       class="filter-dropdown-item <?= $categoryId === 0 ? 'active' : '' ?>">All</a>
                    <?php foreach ($categoriesArr as $cat): ?>
                        <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'index', '?' => array_filter(['category' => $cat->id, 'min_price' => $minPrice, 'max_price' => $maxPrice, 'sort' => $sortBy !== 'newest' ? $sortBy : null])]) ?>"
                           class="filter-dropdown-item <?= $categoryId === $cat->id ? 'active' : '' ?>">
                            <?= h($cat->name) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Price Dropdown -->
            <div class="filter-dropdown" id="filter-price">
                <button type="button" class="filter-dropdown-btn <?= ($minPrice || $maxPrice) ? 'is-active' : '' ?>" aria-expanded="false" aria-controls="filter-price-menu">
                    Price
                    <?php if ($minPrice || $maxPrice): ?>
                        <span class="filter-active-label">: $<?= $minPrice ?: '0' ?> — $<?= $maxPrice ?: '∞' ?></span>
                    <?php endif; ?>
                    <svg class="filter-chevron" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </button>
                <div class="filter-dropdown-menu filter-dropdown-menu--price" id="filter-price-menu">
                    <?= $this->Form->hidden('category', ['value' => $categoryId ?: '']) ?>
                    <?= $this->Form->hidden('sort', ['value' => $sortBy]) ?>
                    <div class="price-dropdown-row">
                        <label class="price-field">
                            <span class="price-field-label">Min</span>
                            <span class="price-field-control">
                                <span class="price-prefix">$</span>
                                <?= $this->Form->number('min_price', ['placeholder' => '0', 'value' => $minPrice, 'class' => 'price-input', 'min' => 0, 'step' => 1]) ?>
                            </span>
                        </label>
                        <span class="price-sep">to</span>
                        <label class="price-field">
                            <span class="price-field-label">Max</span>
                            <span class="price-field-control">
                                <span class="price-prefix">$</span>
                                <?= $this->Form->number('max_price', ['placeholder' => 'Any', 'value' => $maxPrice, 'class' => 'price-input', 'min' => 0, 'step' => 1]) ?>
                            </span>
                        </label>
                    </div>
                    <button type="submit" class="filter-apply-btn">Apply</button>
                </div>
            </div>

            <!-- Sort Dropdown -->
            <div class="filter-dropdown filter-dropdown--right" id="filter-sort">
                <?php
                $sorts = ['newest' => 'Newest', 'price_asc' => 'Price: Low to High', 'price_desc' => 'Price: High to Low', 'featured' => 'Featured', 'bestsales' => 'Best Sales'];
                $sortLabel = $sorts[$sortBy] ?? 'Newest';
                ?>
                <button type="button" class="filter-dropdown-btn <?= $sortBy !== 'newest' ? 'is-active' : '' ?>" aria-expanded="false" aria-controls="filter-sort-menu">
                    Sort by: <span class="filter-active-label"><?= $sortLabel ?></span>
                    <svg class="filter-chevron" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </button>
                <div class="filter-dropdown-menu filter-dropdown-menu--sort" id="filter-sort-menu">
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
                <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'index']) ?>"
                   class="filter-clear-btn">Clear</a>
            <?php endif; ?>

        </div>

        <?= $this->Form->end() ?>
    </div>

    <script>
        (function () {
            var dropdowns = document.querySelectorAll('.jewelry-page .filter-dropdown');

            function closeDropdown(dd) {
                dd.classList.remove('open');
                var btn = dd.querySelector('.filter-dropdown-btn');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            }

            dropdowns.forEach(function (dd) {
                var btn = dd.querySelector('.filter-dropdown-btn');
                if (!btn) return;
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var isOpen = dd.classList.contains('open');
                    dropdowns.forEach(closeDropdown);
                    if (!isOpen) {
                        dd.classList.add('open');
                        btn.setAttribute('aria-expanded', 'true');
                    }
                });
            });

            document.addEventListener('click', function () {
                dropdowns.forEach(closeDropdown);
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') dropdowns.forEach(closeDropdown);
            });

            document.querySelectorAll('.jewelry-page .filter-dropdown-menu').forEach(function (menu) {
                menu.addEventListener('click', function (e) { e.stopPropagation(); });
            });
        })();
    </script>

    <?php
    $identity    = $this->request->getAttribute('identity');
    $isAdmin     = $identity && $identity->get('role') === 'admin';
    $wishlistIds = $wishlistIds ?? [];
    ?>

    <?php if ($products->isEmpty()): ?>
        <div class="empty-state">
            <p>No products found. <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'index']) ?>">Clear filters</a></p>
        </div>
    <?php else: ?>

        <div class="product-grid">

            <!-- Quick-add tile — only visible to admins -->
            <?php if ($isAdmin): ?>
                <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'add', '?' => ['type' => 'jewelry']]) ?>" class="product-card-link">
                    <div class="product-card product-card--add">
                        <div class="product-card-add-icon">+</div>
                        <p class="product-card-add-label">Add Product</p>
                    </div>
                </a>
            <?php endif; ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card-wrap">
                <a href="<?= $this->Url->build('/jewelry/view/' . $product->id) ?>?back=/jewelry" class="product-card-link">
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
                    <?= $this->element('wishlist_heart') ?>
                </button>
                <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
