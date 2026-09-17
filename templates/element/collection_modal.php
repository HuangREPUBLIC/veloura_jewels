<?php
/**
 * Shared "Explore Our Collections" modal.
 * Used on the home page (Pages/home.php).
 *
 * @var \App\View\AppView $this
 */
$collections = [
    [
        'url' => ['controller' => 'Jewelry', 'action' => 'index'],
        'image' => $siteSettings['modal_jewellery_image'] ?? 'greenNecklace.jpg',
        'name' => 'Jewellery',
        'description' => 'Rings, necklaces, bracelets & more',
    ],
    [
        'url' => ['controller' => 'Jewelry', 'action' => 'homeDecor'],
        'image' => $siteSettings['modal_homedecor_image'] ?? 'oliveVase.jpg',
        'name' => 'Home Décor',
        'description' => 'Handcrafted accents for your space',
    ],
];
?>
<div class="collection-modal" id="collectionModal">
    <div class="collection-modal-overlay" data-modal-close></div>
    <div class="collection-modal-content" role="dialog" aria-modal="true"
         aria-labelledby="collectionModalTitle" aria-describedby="collectionModalSub" tabindex="-1">
        <div class="collection-modal-header">
            <span class="collection-modal-handle" aria-hidden="true"></span>
            <h2 class="collection-modal-title" id="collectionModalTitle"><?= h($modalTitle ?? 'Explore Our Collections') ?></h2>
            <p class="collection-modal-sub" id="collectionModalSub"><?= h($modalSubtitle ?? 'What are you looking for?') ?></p>
        </div>
        <button class="collection-modal-close" type="button" aria-label="Close" data-modal-close>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
        <div class="collection-modal-cards">
            <?php foreach ($collections as $collection): ?>
                <a href="<?= $this->Url->build($collection['url']) ?>" class="collection-modal-card">
                    <span class="collection-modal-card-media">
                        <?= $this->Html->image($collection['image'], ['alt' => '', 'class' => 'collection-modal-card-img', 'loading' => 'lazy']) ?>
                    </span>
                    <span class="collection-modal-card-body">
                        <span class="collection-modal-card-name"><?= h($collection['name']) ?></span>
                        <span class="collection-modal-card-desc"><?= h($collection['description']) ?></span>
                    </span>
                    <svg class="collection-modal-card-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="13 6 19 12 13 18"/>
                    </svg>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
