<?php
$this->Html->css('pages', ['block' => true]);
$this->assign('title', 'Our Story');
?>

<div class="story">

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
        <h2 class="story-values-title">Our Values</h2>
        <div class="story-values-grid">
            <?php foreach ([
                'Timeless Elegance' => 'We believe great design never goes out of style. Our collections are created to feel sophisticated, versatile, and enduring.',
                'Quality with Purpose' => 'Every item is chosen with care and attention to craftsmanship so our customers can enjoy pieces that are both beautiful and lasting.',
                'Personal Expression' => 'Jewellery and décor are more than accessories. They reflect personality, memories, and lifestyle, and we encourage individuality in every collection.',
                'Warmth & Connection' => 'Veloura Jewels is built on creating meaningful connections with our customers through thoughtful products and genuine service.',
                'Beauty in Everyday Moments' => 'We believe luxury should be part of daily life, in the jewellery you wear, the gifts you give, and the spaces you create at home.',
                'Customer First' => 'At the heart of Veloura Jewels is a commitment to making every customer feel valued, inspired, and confident in what they choose.',
            ] as $title => $text): ?>
                <div class="story-value">
                    <h3 class="story-value-title"><?= h($title) ?></h3>
                    <p><?= h($text) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

</div>
