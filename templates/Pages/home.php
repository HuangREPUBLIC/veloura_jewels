<?php
$this->assign('title', 'Home');
$this->Html->css('home', ['block' => true]);
?>

<div class="home-page">

    <!-- HERO -->
    <section class="hp-hero">
        <div class="hp-hero-text">
            <div class="hp-hero-ornament"></div>
            <p class="hp-label"><?= h($pageContent['hero_label'] ?? '') ?></p>
            <h1 class="hp-hero-heading"><?= h($pageContent['hero_heading'] ?? '') ?></h1>
            <p class="hp-hero-sub"><?= h($pageContent['hero_subtext'] ?? '') ?></p>
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
                                <h3 class="product-name"><?= h($product->name) ?></h3>
                                <p class="product-price">$<?= number_format((float)$product->sale_price, 2) ?></p>
                            </div>
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
            <div class="hp-story-image-tag"><?= h($pageContent['brand_story_tag'] ?? '') ?></div>
        </div>
        <div class="hp-story-text">
            <p class="hp-label">Our Journey</p>
            <h2 class="hp-story-title">About<br>Veloura Jewels</h2>
            <div class="hp-story-divider"></div>
            <p class="hp-story-body"><?= nl2br(h($pageContent['brand_story_body_1'] ?? '')) ?></p>
            <p class="hp-story-body"><?= nl2br(h($pageContent['brand_story_body_2'] ?? '')) ?></p>
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
