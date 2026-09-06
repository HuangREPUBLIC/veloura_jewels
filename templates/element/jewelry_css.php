<?php
/**
 * The jewellery CSS bundle.
 *
 * These files used to be pulled by @import inside webroot/css/jewelry.css,
 * which meant they never appeared as their own <link> and so never picked up
 * the Asset.timestamp cache buster. Listing them here gives each one a
 * versioned URL, so an edit reaches browsers on the next page load.
 *
 * @var \App\View\AppView $this
 * @var bool|null $block  false for pages that echo their own <head> markup
 */
?>
<?= $this->Html->css([
    'jewelry/product-card',
    'jewelry/product-detail',
    'jewelry/confirm',
    'jewelry/filters',
    'jewelry/payment',
    'jewelry/wishlist-btn',
], ['block' => $block ?? true]) ?>
