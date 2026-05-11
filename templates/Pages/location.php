<?php $this->Html->css('faq', ['block' => true]); ?>
<?php $this->assign('title', 'Find Us'); ?>

<div class="location-page">

    <header class="faq-header">
        <h2><?= h($pageContent['page_heading'] ?? 'Find Us') ?></h2>
        <?php if (!empty($pageContent['intro'])): ?>
            <p><?= h($pageContent['intro']) ?></p>
        <?php endif; ?>
    </header>

    <div class="location-content">

        <?php if (!empty($pageContent['store_image'])): ?>
            <div class="location-hero">
                <?= $this->Html->image(h($pageContent['store_image']), ['alt' => 'Veloura Jewels Store', 'class' => 'location-hero-img']) ?>
            </div>
        <?php endif; ?>

        <div class="location-cards">

            <div class="location-card">
                <div class="location-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" width="28" height="28">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>
                <h3 class="location-card-title">Address</h3>
                <p class="location-card-text"><?= h($pageContent['address'] ?? '') ?></p>
            </div>

            <div class="location-card">
                <div class="location-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" width="28" height="28">
                        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 10a19.79 19.79 0 01-3-8.57A2 2 0 012 1.28h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 9.09a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                    </svg>
                </div>
                <h3 class="location-card-title">Phone</h3>
                <p class="location-card-text"><?= h($pageContent['phone'] ?? '') ?></p>
            </div>

            <div class="location-card">
                <div class="location-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" width="28" height="28">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <h3 class="location-card-title">Opening Hours</h3>
                <p class="location-card-text"><?= h($pageContent['hours'] ?? '') ?></p>
            </div>

        </div>

    </div>

</div>
