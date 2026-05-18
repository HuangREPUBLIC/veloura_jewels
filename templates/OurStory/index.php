<?php $this->Html->css('faq', ['block' => true]); ?>

<div class="story-page">

    <header class="story-hero" style="background-image: linear-gradient(90deg, rgba(24,56,47,0.82), rgba(24,56,47,0.28)), url('<?= $this->Url->build('/img/' . h($pageContent['our_story_background_image'] ?? 'storeInterior.png')) ?>')">
        <p class="story-label">Est. 2024</p>
        <h1 class="story-hero-title"><?= h($pageContent['page_heading'] ?? 'Our Story') ?></h1>
        <p class="story-hero-sub"><?= h($pageContent['hero_sub'] ?? '') ?></p>
    </header>

    <section class="story-section--body" style="--story-img: url('<?= $this->Url->build('/img/' . h($pageContent['story_body_image'] ?? 'greenNecklace.jpg')) ?>')">
        <div class="story-prose">
            <?php foreach (['body_1', 'body_2', 'body_3', 'body_4'] as $key): ?>
                <?php if (!empty($pageContent[$key])): ?>
                    <p><?= h($pageContent[$key]) ?></p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </section>
    <section class="story-values">
        <div class="story-values-header">
            <div class="story-values-ornament"></div>
            <h2 class="story-values-title">Our Values</h2>
        </div>
        <div class="story-values-grid">

            <div class="story-value-card">
                <div class="story-value-ornament"></div>
                <h3 class="story-value-title">Timeless Elegance</h3>
                <p>We believe great design never goes out of style. Our collections are created to feel sophisticated, versatile, and enduring.</p>
            </div>

            <div class="story-value-card">
                <div class="story-value-ornament"></div>
                <h3 class="story-value-title">Quality with Purpose</h3>
                <p>Every item is chosen with care and attention to craftsmanship so our customers can enjoy pieces that are both beautiful and lasting.</p>
            </div>

            <div class="story-value-card">
                <div class="story-value-ornament"></div>
                <h3 class="story-value-title">Personal Expression</h3>
                <p>Jewellery and décor are more than accessories — they are reflections of personality, memories, and lifestyle. We encourage individuality in every collection.</p>
            </div>

            <div class="story-value-card">
                <div class="story-value-ornament"></div>
                <h3 class="story-value-title">Warmth &amp; Connection</h3>
                <p>Veloura Jewels is built on creating meaningful connections with our customers through thoughtful products and genuine service.</p>
            </div>

            <div class="story-value-card">
                <div class="story-value-ornament"></div>
                <h3 class="story-value-title">Beauty in Everyday Moments</h3>
                <p>We believe luxury should be accessible in daily life — in the jewellery you wear, the gifts you give, and the spaces you create at home.</p>
            </div>

            <div class="story-value-card">
                <div class="story-value-ornament"></div>
                <h3 class="story-value-title">Customer First</h3>
                <p>At the heart of Veloura Jewels is a commitment to making every customer feel valued, inspired, and confident in what they choose.</p>
            </div>

        </div>
    </section>

</div>
