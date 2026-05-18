<?php
$this->assign('title', 'Home');
$this->Html->css(['home', 'jewelry'], ['block' => true]);

$identity    = $this->request->getAttribute('identity');
$wishlistIds = $wishlistIds ?? [];
?>

    <div class="home-page">

        <!-- HERO -->
        <section class="hp-hero">
            <div class="hero-panel">
                <video class="home-video" autoplay muted loop playsinline>
                    <source src="<?= $this->Url->build('/img/' . h($pageContent['home_video'])) ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>

                <div class="hp-hero-overlay">
                    <h1 class="hp-hero-heading"><?= nl2br(h($pageContent['hero_heading'] ?? "Jewels that tell\nyour story.")) ?></h1>
                    <p class="hp-hero-sub"><?= h($pageContent['hero_subtext'] ?? '') ?></p>
                    <div class="hp-hero-actions">
                        <button class="hp-btn hp-btn--primary" id="explore-btn" type="button">Explore the Collection</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- COLLECTION MODAL -->
        <?= $this->element('collection_modal') ?>

        <!-- NEW ARRIVALS -->
        <?php if (!empty($featuredProducts)): ?>
            <section class="hp-arrivals">
                <div class="hp-section-header">
                    <h2 class="hp-section-header-title">New Arrivals</h2>
                </div>
                <div class="hp-arrivals-grid">
                    <?php foreach ($featuredProducts as $product): ?>
                        <?php
                        $img = !empty($product->product_images) ? $product->product_images[0]->filename : null;
                        $imgSrc = $img
                            ? $this->Url->build('/img/products/' . rawurlencode($img))
                            : $this->Url->build('/img/homepage.png');
                        $isHomeDecor = ($product->category->type ?? '') === 'home_decor';
                        $productUrl = $isHomeDecor
                            ? $this->Url->build('/home-decor/view/' . $product->id) . '?back=/'
                            : $this->Url->build(['controller' => 'Jewelry', 'action' => 'view', $product->id]) . '?back=/';
                        ?>
                        <div class="product-card-wrap">
                            <a href="<?= $productUrl ?>" class="product-card-link">
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
                                        <img src="<?= $imgSrc ?>" alt="<?= h($product->name) ?>" class="product-image product-image--primary" loading="lazy">
                                        <?php if (!empty($product->product_images[1])): ?>
                                            <img src="<?= $this->Url->build('/img/products/' . rawurlencode($product->product_images[1]->filename)) ?>"
                                                 alt="<?= h($product->name) ?>"
                                                 class="product-image product-image--hover" loading="lazy">
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-card-body">
                                        <h4 class="product-name"><?= h($product->name) ?></h4>
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
            </section>
        <?php endif; ?>

        <!-- FEATURED JEWELLERY -->
        <?php if (!empty($featuredJewelry)): ?>
            <section class="hp-featured">
                <div class="hp-featured-inner">
                    <div class="hp-featured-image">
                        <?= $this->Html->image($pageContent['featured_jewellery_image'], ['alt' => 'Featured collection', 'class' => 'featured-jewellery-image']) ?>
                    </div>
                    <div class="hp-featured-content">
                        <h2 class="hp-featured-title"><?= h($pageContent['featured_jewellery_title'] ?? 'Every piece has a story.') ?></h2>
                        <p class="hp-featured-sub"><?= h($pageContent['featured_jewellery_subtext'] ?? '') ?></p>
                        <div class="hp-featured-products">
                            <?php foreach ($featuredJewelry as $product): ?>
                                <?php
                                $img = !empty($product->product_images) ? $product->product_images[0]->filename : null;
                                $imgSrc = $img
                                    ? $this->Url->build('/img/products/' . rawurlencode($img))
                                    : $this->Url->build('/img/homepage.png');
                                $productUrl = $this->Url->build(['controller' => 'Jewelry', 'action' => 'view', $product->id]) . '?back=/';
                                ?>
                                <div class="product-card-wrap">
                                    <a href="<?= $productUrl ?>" class="product-card-link">
                                        <div class="product-card">
                                            <div class="product-image-wrapper">
                                                <img src="<?= $imgSrc ?>" alt="<?= h($product->name) ?>" class="product-image product-image--primary" loading="lazy">
                                            </div>
                                            <div class="product-card-body">
                                                <p class="product-price">$<?= number_format((float)$product->sale_price, 2) ?></p>
                                                <h4 class="product-name"><?= h($product->name) ?></h4>
                                            </div>
                                        </div>
                                    </a>
                                    <?php if ($identity): ?>
                                        <button class="wishlist-btn<?= in_array($product->id, $wishlistIds) ? ' wishlisted' : '' ?>"
                                                data-product-id="<?= $product->id ?>"
                                                type="button"
                                                aria-label="Save to wishlist">
                                            <svg width="20" height="20" viewBox="0 0 64 64" fill="none">
                                                <path d="M32,57C31,56.5 5,42 5,23.5C5,13.8 12.2,6.5 21,6.5C26,6.5 30.4,9 32,11.2C33.6,9 38,6.5 43,6.5C51.8,6.5 59,13.8 59,23.5C59,42 33,56.5 32,57Z"/>
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- FEATURED HOME DECOR -->
        <?php if (!empty($featuredHomeDecor)): ?>
            <section class="hp-featured hp-featured--reverse">
                <div class="hp-featured-inner">
                    <div class="hp-featured-content">
                        <h2 class="hp-featured-title"><?= h($pageContent['featured_home_title'] ?? 'Make your space yours.') ?></h2>
                        <p class="hp-featured-sub"><?= h($pageContent['featured_home_subtext'] ?? '') ?></p>
                        <div class="hp-featured-products">
                            <?php foreach ($featuredHomeDecor as $product): ?>
                                <?php
                                $img = !empty($product->product_images) ? $product->product_images[0]->filename : null;
                                $imgSrc = $img
                                    ? $this->Url->build('/img/products/' . rawurlencode($img))
                                    : $this->Url->build('/img/homepage.png');
                                $productUrl = $this->Url->build('/home-decor/view/' . $product->id) . '?back=/';
                                ?>
                                <div class="product-card-wrap">
                                    <a href="<?= $productUrl ?>" class="product-card-link">
                                        <div class="product-card">
                                            <div class="product-image-wrapper">
                                                <img src="<?= $imgSrc ?>" alt="<?= h($product->name) ?>" class="product-image product-image--primary" loading="lazy">
                                            </div>
                                            <div class="product-card-body">
                                                <p class="product-price">$<?= number_format((float)$product->sale_price, 2) ?></p>
                                                <h4 class="product-name"><?= h($product->name) ?></h4>
                                            </div>
                                        </div>
                                    </a>
                                    <?php if ($identity): ?>
                                        <button class="wishlist-btn<?= in_array($product->id, $wishlistIds) ? ' wishlisted' : '' ?>"
                                                data-product-id="<?= $product->id ?>"
                                                type="button"
                                                aria-label="Save to wishlist">
                                            <svg width="20" height="20" viewBox="0 0 64 64" fill="none">
                                                <path d="M32,57C31,56.5 5,42 5,23.5C5,13.8 12.2,6.5 21,6.5C26,6.5 30.4,9 32,11.2C33.6,9 38,6.5 43,6.5C51.8,6.5 59,13.8 59,23.5C59,42 33,56.5 32,57Z"/>
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="hp-featured-img-decor">
                        <?= $this->Html->image($pageContent['featured_homedecor_image'] ?? 'homedecor.png', ['alt' => 'Featured collection']) ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- BRAND STORY -->
        <section class="hp-story">
            <div class="hp-story-image-wrap">
                <video class="home-video" autoplay muted loop playsinline>
                    <source src="<?= $this->Url->build('/img/' . h($pageContent['diamond_ring_video'] ?? 'handmadeDiamondRing.mp4')) ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="hp-story-text">
                <p class="hp-label"><?= h($pageContent['brand_story_tag'] ?? 'Our Journey') ?></p>
                <h2 class="hp-story-title"><?= nl2br(h($pageContent['brand_story_title'] ?? "About\nVeloura Jewels")) ?></h2>
                <div class="hp-story-divider"></div>
                <p class="hp-story-body"><?= h($pageContent['brand_story_body_1'] ?? '') ?></p>
                <p class="hp-story-body"><?= h($pageContent['brand_story_body_2'] ?? '') ?></p>
                <?= $this->Html->link('Our full story →', ['controller' => 'OurStory', 'action' => 'index'], ['class' => 'hp-story-link']) ?>
            </div>
        </section>

        <!-- CONTACT CTA -->
        <section class="hp-cta">
            <div class="hp-cta-inner">
                <div class="hp-cta-ornament"></div>
                <p class="hp-label"><?= h($pageContent['contact_cta_label'] ?? "We'd love to hear from you") ?></p>
                <h2 class="hp-cta-title"><?= h($pageContent['contact_cta_title'] ?? 'Have a question or a special request?') ?></h2>
                <p class="hp-cta-sub"><?= h($pageContent['contact_cta_subtext'] ?? '') ?></p>
                <?= $this->Html->link('Get in Touch', ['controller' => 'ContactSubmissions', 'action' => 'add'], ['class' => 'hp-btn hp-btn--cta']) ?>
            </div>
        </section>

    </div>


<?php
$this->Html->scriptBlock("window.collectionModalTriggerId = 'explore-btn';", ['block' => true]);
$this->Html->script('collection-modal', ['block' => true]);
?>
