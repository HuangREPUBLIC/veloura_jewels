<?php
$this->assign('title', 'Home');
$this->Html->css('home', ['block' => true]);
?>

<div class="home-page">

    <!-- HERO -->
    <section class="hp-hero">
        <div class="hp-hero-text">
            <div class="hp-hero-ornament"></div>
            <p class="hp-label">Handcrafted with love</p>
            <h1 class="hp-hero-heading">Jewels that<br>tell your<br>story.</h1>
            <p class="hp-hero-sub">Artisan-made pieces, each shaped by hand to bring warmth and meaning into your everyday.</p>
            <div class="hp-hero-actions">
                <?= $this->Html->link('Explore the Collection', ['controller' => 'Jewelry', 'action' => 'index'], ['class' => 'hp-btn hp-btn--primary']) ?>
                <?= $this->Html->link('Shop Home Décor →', ['controller' => 'Jewelry', 'action' => 'homeDecor'], ['class' => 'hp-hero-alt-link']) ?>
            </div>
        </div>
        <div class="hp-hero-image">
            <?= $this->Html->image('homepage.png', ['alt' => 'Veloura Jewels collection', 'class' => 'hp-img-fill']) ?>
            <div class="hp-hero-warm-overlay"></div>
        </div>
    </section>

    <!-- NEW ARRIVALS -->
    <?php if (!empty($featuredProducts)): ?>
        <section class="hp-arrivals">
            <div class="hp-section-header">
                <p class="hp-label">Just in</p>
                <h2 class="hp-section-header-title">New Arrivals</h2>
            </div>
            <div class="hp-arrivals-grid">
                <?php foreach ($featuredProducts as $product): ?>
                    <?php
                    $img = !empty($product->product_images) ? $product->product_images[0]->filename : null;
                    $imgSrc = $img
                        ? $this->Url->build('/img/products/' . rawurlencode($img))
                        : $this->Url->build('/img/homepage.png');
                    $isHomeDecor = false;
                    foreach ($product->categories as $cat) {
                        if ($cat->type === 'home_decor') { $isHomeDecor = true; break; }
                    }
                    $productUrl = $isHomeDecor
                        ? $this->Url->build('/home-decor/view/' . $product->id)
                        : $this->Url->build(['controller' => 'Jewelry', 'action' => 'view', $product->id]);
                    ?>
                    <a href="<?= $productUrl ?>" class="hp-product-card">
                        <div class="hp-product-card-img-wrap<?= !empty($product->product_images[1]) ? ' has-hover-image' : '' ?>">
                            <?php if (!empty($product->featured)): ?>
                                <div class="product-card-badges">
                                    <span class="product-badge product-badge--featured">Featured</span>
                                </div>
                            <?php endif; ?>
                            <img src="<?= $imgSrc ?>" alt="<?= h($product->name) ?>" class="hp-product-card-img hp-product-card-img--primary" loading="lazy">
                            <?php if (!empty($product->product_images[1])): ?>
                                <img src="<?= $this->Url->build('/img/products/' . rawurlencode($product->product_images[1]->filename)) ?>"
                                     alt="<?= h($product->name) ?>"
                                     class="hp-product-card-img hp-product-card-img--hover" loading="lazy">
                            <?php endif; ?>
                        </div>
                        <div class="hp-product-card-info">
                            <h4 class="hp-product-card-name"><?= h($product->name) ?></h4>
                            <p class="hp-product-card-price">$<?= number_format((float)$product->sale_price, 2) ?></p>
                        </div>
                    </a>
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
            <?= $this->Html->link('Our full story →', ['controller' => 'Pages', 'action' => 'location'], ['class' => 'hp-story-link']) ?>
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
