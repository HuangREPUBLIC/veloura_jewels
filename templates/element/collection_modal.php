<?php
/**
 * Shared "Explore Our Collections" modal.
 * Used on the home page (Pages/home.php) and the cart page (Jewelry/cart.php).
 *
 * @var \App\View\AppView $this
 */
?>
<div class="collection-modal" id="collectionModal">
    <div class="collection-modal-overlay"></div>
    <div class="collection-modal-content">
        <button class="collection-modal-close" type="button" aria-label="Close">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
        <h2 class="collection-modal-title"><?= h($modalTitle ?? 'Explore Our Collections') ?></h2>
        <p class="collection-modal-sub"><?= h($modalSubtitle ?? 'What are you looking for?') ?></p>
        <div class="collection-modal-cards">
            <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'index']) ?>" class="collection-modal-card">
                <?= $this->Html->image($siteSettings['modal_jewellery_image'] ?? 'greenNecklace.jpg', ['alt' => 'Jewellery collection', 'class' => 'collection-modal-card-img']) ?>
                <div class="collection-modal-card-body">
                    <h3>Jewellery</h3>
                    <p>Rings, necklaces, bracelets &amp; more</p>
                </div>
            </a>
            <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'homeDecor']) ?>" class="collection-modal-card">
                <?= $this->Html->image($siteSettings['modal_homedecor_image'] ?? 'oliveVase.jpg', ['alt' => 'Home décor collection', 'class' => 'collection-modal-card-img']) ?>
                <div class="collection-modal-card-body">
                    <h3>Home Décor</h3>
                    <p>Handcrafted accents for your space</p>
                </div>
            </a>
        </div>
    </div>
</div>

