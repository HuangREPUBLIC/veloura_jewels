<?php
$this->assign('title', 'Home');
$this->Html->css(['home', 'jewelry'], ['block' => true]);
?>

<div class="home-page">

    <!-- HERO -->
    <section class="hp-hero">
        <div class="hero-panel">
            <video class="home-video" autoplay muted loop playsinline>
                <source src="webroot/videos/homeVideo.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>

            <div class="hp-hero-overlay">
                <?php if (!empty($pageContent['hero_label'])): ?>
                    <p class="hp-label"><?= h($pageContent['hero_label']) ?></p>
                <?php endif; ?>
                <h1 class="hp-hero-heading"><?= nl2br(h($pageContent['hero_heading'] ?? 'Jewels that tell your story.')) ?></h1>
                <p class="hp-hero-sub"><?= h($pageContent['hero_subtext'] ?? 'Artisan-made pieces, each shaped by hand to bring warmth and meaning into your everyday.') ?></p>
                <div class="hp-hero-actions">
                    <button class="hp-btn hp-btn--primary" id="explore-btn" type="button">Explore the Collection</button>
                </div>
            </div>
        </div>
    </section>

    <!-- COLLECTION MODAL -->
    <div class="collection-modal" id="collectionModal">
        <div class="collection-modal-overlay"></div>
        <div class="collection-modal-content">
            <button class="collection-modal-close" type="button" aria-label="Close">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
            <h2 class="collection-modal-title">Explore Our Collections</h2>
            <p class="collection-modal-sub">What are you looking for?</p>
            <div class="collection-modal-cards">
                <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'index']) ?>" class="collection-modal-card">
                    <?= $this->Html->image('greenNecklace.jpg', ['alt' => 'Jewellery collection', 'class' => 'collection-modal-card-img']) ?>
                    <div class="collection-modal-card-body">
                        <h3>Jewellery</h3>
                        <p>Rings, necklaces, bracelets & more</p>
                    </div>
                </a>
                <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'homeDecor']) ?>" class="collection-modal-card">
                    <?= $this->Html->image('oliveVase.jpg', ['alt' => 'Home décor collection', 'class' => 'collection-modal-card-img']) ?>
                    <div class="collection-modal-card-body">
                        <h3>Home Décor</h3>
                        <p>Handcrafted accents for your space</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- NEW ARRIVALS -->
    <?php if (!empty($featuredProducts)): ?>
        <section class="hp-arrivals">
            <div class="hp-section-header">
                <h2 class="hp-section-header-title">New Arrivals</h2>
            </div>
            <?php
            $identity    = $this->request->getAttribute('identity');
            $wishlistIds = $wishlistIds ?? [];
            ?>
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
                        <svg width="20" height="20" viewBox="0 0 64 64" fill="none">
                            <path d="M32,57C31,56.5 5,42 5,23.5C5,13.8 12.2,6.5 21,6.5C26,6.5 30.4,9 32,11.2C33.6,9 38,6.5 43,6.5C51.8,6.5 59,13.8 59,23.5C59,42 33,56.5 32,57Z"/>
                        </svg>
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
                    <?= $this->Html->image('featuredJewellery.png', ['alt' => 'Featured collection']) ?>
                </div>
                <div class="hp-featured-content">
                    <h2 class="hp-featured-title"><?= h($pageContent['featured_jewellery_title'] ?? 'Every piece has a story.') ?></h2>
                    <p class="hp-featured-sub"><?= h($pageContent['featured_jewellery_subtext'] ?? 'Trace the journey that made your jewellery one of a kind.') ?></p>
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
                    <p class="hp-featured-sub"><?= h($pageContent['featured_home_subtext'] ?? 'Handcrafted home accents designed to bring warmth and character to every room.') ?></p>
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
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="hp-featured-img-decor">
                    <?= $this->Html->image('homedecor.png', ['alt' => 'Featured collection']) ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- BRAND STORY -->
    <section class="hp-story">
        <div class="hp-story-image-wrap">
            <video class="home-video" autoplay muted loop playsinline>
                <source src="webroot/videos/handmadeDiamondRing.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>

        </div>
        <div class="hp-story-text">
            <p class="hp-label"><?= h($pageContent['brand_story_tag'] ?? 'Our Journey') ?></p>
            <h2 class="hp-story-title"><?= nl2br(h($pageContent['brand_story_title'] ?? 'About Veloura Jewels')) ?></h2>
            <div class="hp-story-divider"></div>
            <p class="hp-story-body"><?= h($pageContent['brand_story_body_1'] ?? 'Founded by Sarah Smith in Brooksdale, Veloura Jewels is dedicated to creating unique, handcrafted pieces that blend creativity and meaningful design. Every ring, necklace, and home accent is shaped with intention — to bring beauty and elegance into your home and wardrobe.') ?></p>
            <p class="hp-story-body"><?= h($pageContent['brand_story_body_2'] ?? "We believe that what you wear and what surrounds you should feel personal. That's why no two Veloura pieces are exactly alike.") ?></p>
            <?= $this->Html->link('Our full story →', ['controller' => 'OurStory', 'action' => 'index'], ['class' => 'hp-story-link']) ?>
        </div>
    </section>

    <!-- CONTACT CTA -->
    <section class="hp-cta">
        <div class="hp-cta-inner">
            <div class="hp-cta-ornament"></div>
            <p class="hp-label"><?= h($pageContent['contact_cta_label'] ?? "We'd love to hear from you") ?></p>
            <h2 class="hp-cta-title"><?= h($pageContent['contact_cta_title'] ?? 'Have a question or a special request?') ?></h2>
            <p class="hp-cta-sub"><?= h($pageContent['contact_cta_subtext'] ?? "Our team is always happy to help — whether it's a custom order, a gift idea, or just a chat about what we make.") ?></p>
            <?= $this->Html->link('Get in Touch', ['controller' => 'ContactSubmissions', 'action' => 'add'], ['class' => 'hp-btn hp-btn--cta']) ?>        </div>
    </section>

</div>


<?php $this->Html->scriptBlock("
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('explore-btn');
        const modal = document.getElementById('collectionModal');
        const overlay = modal.querySelector('.collection-modal-overlay');
        const close = modal.querySelector('.collection-modal-close');

        btn.addEventListener('click', () => modal.classList.add('is-open'));
        overlay.addEventListener('click', () => modal.classList.remove('is-open'));
        close.addEventListener('click', () => modal.classList.remove('is-open'));

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') modal.classList.remove('is-open');
        });
    });
", ['block' => true]); ?>
