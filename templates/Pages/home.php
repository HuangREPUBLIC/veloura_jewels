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
                <h1 class="hp-hero-heading">Jewels that tell <br> your story.</h1>
                <p class="hp-hero-sub">Artisan-made pieces, each shaped by hand to bring warmth and meaning into your everyday.</p>
                <div class="hp-hero-actions">
                    <?= $this->Html->link('Explore the Collection', ['controller' => 'Jewelry', 'action' => 'index'], ['class' => 'hp-btn hp-btn--primary']) ?>
                </div>
            </div>
        </div>
    </section>

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
            <div class="hp-arrivals-footer">
                <?= $this->Html->link('All Jewelry →', ['controller' => 'Jewelry', 'action' => 'index'], ['class' => 'hp-arrivals-link']) ?>
                <span class="hp-arrivals-link-sep">·</span>
                <?= $this->Html->link('All Home Décor →', ['controller' => 'Jewelry', 'action' => 'homeDecor'], ['class' => 'hp-arrivals-link']) ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- BRAND STORY -->
    <section class="hp-story">
        <div class="hp-story-image-wrap">
            <?= $this->Html->image('homepage.png', ['alt' => 'Sarah Smith — Veloura Jewels founder', 'class' => 'hp-img-fill']) ?>
            <div class="hp-story-image-tag">Est. Brooksdale</div>
        </div>
        <div class="hp-story-text">
            <p class="hp-label">Our Journey</p>
            <h2 class="hp-story-title">About<br>Veloura Jewels</h2>
            <div class="hp-story-divider"></div>
            <p class="hp-story-body">Founded by Sarah Smith in Brooksdale, Veloura Jewels is dedicated to creating unique, handcrafted pieces that blend creativity and meaningful design. Every ring, necklace, and home accent is shaped with intention — to bring beauty and elegance into your home and wardrobe.</p>
            <p class="hp-story-body">We believe that what you wear and what surrounds you should feel personal. That's why no two Veloura pieces are exactly alike.</p>
            <?= $this->Html->link('Our full story →', ['controller' => 'OurStory', 'action' => 'index'], ['class' => 'hp-story-link']) ?>
        </div>
    </section>

    <!-- CONTACT CTA -->
    <section class="hp-cta">
        <div class="hp-cta-inner">
            <div class="hp-cta-ornament"></div>
            <p class="hp-label">We'd love to hear from you</p>
            <h2 class="hp-cta-title">Have a question or a special request?</h2>
            <p class="hp-cta-sub">Our team is always happy to help — whether it's a custom order, a gift idea, or just a chat about what we make.</p>
            <?= $this->Html->link('Get in Touch', ['controller' => 'ContactSubmissions', 'action' => 'add'], ['class' => 'hp-btn hp-btn--primary']) ?>
        </div>
    </section>

</div>
