<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'Veloura Jewels';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon', '/img/' . ($siteSettings['icon_image'] ?? 'icon.png')) ?>


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <?= $this->Html->css(['normalize.min', 'fonts', 'default-styles', 'cake', 'search']) ?>


    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>

<?php
$cart = $this->request->getSession()->read('Cart') ?? [];
$count = count($cart);
$identity = $this->request->getAttribute('identity');
$role = $identity ? $identity->get('role') : null;
?>

<header class="navbar">


    <div class="navbar-brand">
        <?= $this->Html->link(
            $this->Html->image($siteSettings['logo_image'] ?? 'logo.png', ['alt' => 'Veloura Jewels', 'class' => 'navbar-logo']),
            '/',
            ['escape' => false]
        ) ?>

        <button class="nav-hamburger" id="navHamburger" onclick="toggleMobileMenu()" title="Menu" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

    </div>


    <?php $currentPath = $this->request->getPath(); ?>
    <nav class="navbar-links" id="navLinks">
        <?= $this->Html->link('Home', '/', ['class' => $currentPath === '/' ? 'active' : '']) ?>
        <?= $this->Html->link('Our Story', '/ourStory', ['class' => str_starts_with($currentPath, '/ourStory') ? 'active' : '']) ?>
        <?= $this->Html->link('Jewellery', '/jewellery', ['class' => str_starts_with($currentPath, '/jewelry') ? 'active' : '']) ?>
        <?= $this->Html->link('Home Decor', '/home-decor', ['class' => str_starts_with($currentPath, '/home-decor') ? 'active' : '']) ?>
       <?= $this->Html->link('FAQ', '/faq', ['class' => str_starts_with($currentPath, '/faq') ? 'active' : '']) ?>
       <?= $this->Html->link('Location', '/location', ['class' => str_starts_with($currentPath, '/location') ? 'active' : '']) ?>

        <?php if (in_array($role, ['admin', 'staff'])): ?>
            <?= $this->Html->link('Dashboard', '/dashboard', ['class' => (bool) array_filter(['/dashboard', '/schedule', '/products', '/users', '/orders', '/cms', '/contact-submissions'], fn($p) => str_starts_with($currentPath, $p)) ? 'active' : '']) ?>

        <?php else : ?>
            <?= $this->Html->link('Contact', '/contact', ['class' => str_starts_with($currentPath, '/contact') ? 'active' : '']) ?>
        <?php endif ?>
    </nav>


    <div class="navbar-right">
        <!-- Search icon -->
        <button class="nav-icon-btn" title="Search" onclick="openSearch()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="7"/><line x1="16.5" y1="16.5" x2="22" y2="22"/>
            </svg>
        </button>

        <!-- Cart icon -->
        <?= $this->Html->link(
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>'
            . ($count > 0 ? '<span class="nav-cart-badge">' . $count . '</span>' : ''),
            ['controller' => 'Jewelry', 'action' => 'cart'],
            ['class' => 'nav-icon-btn nav-cart-wrap', 'escape' => false, 'title' => 'Cart']
        ) ?>

        <!-- Wishlist icon -->
        <?= $this->Html->link(
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>'
            . ($wishlistCount > 0 ? '<span class="nav-cart-badge">' . $wishlistCount . '</span>' : ''),
            ['controller' => 'Profile', 'action' => 'wishlist'],
            ['class' => 'nav-icon-btn nav-cart-wrap', 'escape' => false, 'title' => 'Wishlist']
        ) ?>

        <!-- User dropdown -->
        <div class="nav-dropdown-wrap">
            <button class="nav-icon-btn" id="navUserBtn" onclick="toggleNavDropdown()" title="Account">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                </svg>
            </button>

            <div class="nav-dropdown" id="navDropdown">
                <?php if (!$this->Identity->isLoggedIn()): ?>
                    <?= $this->Html->link(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg> Login',
                        '/auth/login',
                        ['escape' => false]
                    ) ?>

                <?php elseif ($role === 'customer'): ?>
                    <span class="nav-dropdown-label">Hi, <?= h($identity->get('first_name')) ?></span>
                    <?= $this->Html->link(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg> Profile',
                    ['controller' => 'Profile', 'action' => 'index'],
                    ['escape' => false]
                ) ?>
                    <div class="nav-dropdown-divider"></div>
                    <?= $this->Html->link(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg> Logout',
                        '/auth/logout',
                        ['escape' => false, 'class' => 'nav-dropdown-danger']
                    ) ?>

                <?php else: ?>
                    <span class="nav-dropdown-label"> Hi, <?= h($identity->get('first_name')) ?> </span>
                    <?= $this->Html->link(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg> Dashboard',
                    ['controller' => 'Users', 'action' => 'dashboard'],
                    ['escape' => false]
                ) ?>
                    <?= $this->Html->link(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg> Profile',
                    ['controller' => 'Profile', 'action' => 'index'],
                    ['escape' => false]
                ) ?>
                    <div class="nav-dropdown-divider"></div>
                    <?= $this->Html->link(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg> Logout',
                        '/auth/logout',
                        ['escape' => false, 'class' => 'nav-dropdown-danger']
                    ) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<div class="search-panel" id="searchPanel" style="display:none" aria-hidden="true">
    <div class="search-panel-inner">
        <form class="search-panel-form" action="<?= $this->Url->build(['controller' => 'Search', 'action' => 'search']) ?>" method="GET">
            <svg class="search-panel-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="7"/><line x1="16.5" y1="16.5" x2="22" y2="22"/>
            </svg>
            <input
                type="search"
                name="q"
                id="searchInput"
                class="search-panel-input"
                placeholder="Search our collection..."
                autocomplete="off"
                spellcheck="false"
                value="<?= h($this->request->getQuery('q') ?? '') ?>"
            >
            <button type="button" class="search-panel-close" onclick="closeSearch()" aria-label="Close search">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </form>
        <div class="search-suggestions" id="searchSuggestions"></div>
    </div>
    <div class="search-panel-backdrop" onclick="closeSearch()"></div>
</div>

<main class="main-content">
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
</main>

<footer class="footer">
    <div class="footer-top">

        <div class="footer-col footer-col--brand">
            <?= $this->Html->link(
                $this->Html->image($siteSettings['logo_image'] ?? 'logo.png', ['alt' => 'Veloura Jewels', 'class' => 'footer-logo']),
                '/',
                ['escape' => false]
            ) ?>
            <p class="footer-brand-name">Veloura Jewels</p>
            <p class="footer-tagline"><?= h($siteSettings['footer_tagline'] ?? 'Handcrafted jewellery &amp; home décor, made with love in Brooksdale.') ?></p>
            <ul class="footer-socials">
                <li>
                    <a href="#" class="footer-social" aria-label="Facebook">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.183 21.85v-8.868H7.2V9.526h2.983V6.982a4.17 4.17 0 0 1 4.44-4.572 22.33 22.33 0 0 1 2.667.144v3.084h-1.83a1.44 1.44 0 0 0-1.713 1.68v2.208h3.423l-.447 3.456h-2.97v8.868h-3.57Z"/></svg>
                    </a>
                </li>
                <li>
                    <a href="#" class="footer-social" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.4c-2.607 0-2.934.011-3.958.058-1.022.046-1.72.209-2.33.446a4.705 4.705 0 0 0-1.7 1.107 4.706 4.706 0 0 0-1.108 1.7c-.237.611-.4 1.31-.446 2.331C2.41 9.066 2.4 9.392 2.4 12c0 2.607.011 2.934.058 3.958.046 1.022.209 1.72.446 2.33a4.706 4.706 0 0 0 1.107 1.7c.534.535 1.07.863 1.7 1.108.611.237 1.309.4 2.33.446 1.025.047 1.352.058 3.959.058s2.934-.011 3.958-.058c1.022-.046 1.72-.209 2.33-.446a4.706 4.706 0 0 0 1.7-1.107 4.706 4.706 0 0 0 1.108-1.7c.237-.611.4-1.31.446-2.33.047-1.025.058-1.352.058-3.959s-.011-2.934-.058-3.958c-.047-1.022-.209-1.72-.446-2.33a4.706 4.706 0 0 0-1.107-1.7 4.705 4.705 0 0 0-1.7-1.108c-.611-.237-1.31-.4-2.331-.446C14.934 2.41 14.608 2.4 12 2.4Zm0 1.73c2.563 0 2.867.01 3.88.056.935.042 1.443.199 1.782.33.448.174.768.382 1.104.718.336.336.544.656.718 1.104.131.338.287.847.33 1.783.046 1.012.056 1.316.056 3.879 0 2.563-.01 2.867-.056 3.88-.043.935-.199 1.444-.33 1.782a2.974 2.974 0 0 1-.719 1.104 2.974 2.974 0 0 1-1.103.718c-.339.131-.847.288-1.783.33-1.012.046-1.316.056-3.88.056-2.563 0-2.866-.01-3.878-.056-.936-.042-1.445-.199-1.783-.33a2.974 2.974 0 0 1-1.104-.718 2.974 2.974 0 0 1-.718-1.104c-.131-.338-.288-.847-.33-1.783-.047-1.012-.056-1.316-.056-3.879 0-2.563.01-2.867.056-3.88.042-.935.199-1.443.33-1.782.174-.448.382-.768.718-1.104a2.974 2.974 0 0 1 1.104-.718c.338-.131.847-.288 1.783-.33C9.133 4.14 9.437 4.13 12 4.13Zm0 11.07a3.2 3.2 0 1 1 0-6.4 3.2 3.2 0 0 1 0 6.4Zm0-8.13a4.93 4.93 0 1 0 0 9.86 4.93 4.93 0 0 0 0-9.86Zm6.276-.194a1.152 1.152 0 1 1-2.304 0 1.152 1.152 0 0 1 2.304 0Z"/></svg>
                    </a>
                </li>
                <li>
                    <a href="#" class="footer-social" aria-label="Pinterest">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24"><path fill-rule="evenodd" clip-rule="evenodd" d="M11.765 2.401c3.59-.054 5.837 1.4 6.895 3.95.349.842.722 2.39.442 3.675-.112.512-.144 1.048-.295 1.53-.308.983-.708 1.853-1.238 2.603-.72 1.02-1.81 1.706-3.182 2.052-1.212.305-2.328-.152-2.976-.643-.206-.156-.483-.36-.56-.643h-.029c-.046.515-.244 1.062-.383 1.531-.193.65-.23 1.321-.472 1.929a12.345 12.345 0 0 1-.942 1.868c-.184.302-.692 1.335-1.061 1.347-.04-.078-.057-.108-.06-.245-.118-.19-.035-.508-.087-.766-.082-.4-.145-1.123-.06-1.53v-.643c.096-.442.092-.894.207-1.317.25-.92.39-1.895.648-2.848.249-.915.477-1.916.678-2.847.045-.21-.21-.815-.265-1.041-.174-.713-.042-1.7.176-2.236.275-.674 1.08-1.703 2.122-1.439.838.212 1.371 1.118 1.09 2.266-.295 1.205-.677 2.284-.943 3.49-.068.311.05.641.118.827.248.672 1 1.324 2.004 1.072 1.52-.383 2.193-1.76 2.652-3.246.124-.402.109-.781.206-1.225.204-.935.118-2.331-.177-3.061-.472-1.17-1.353-1.92-2.563-2.328L12.707 4.3c-.56-.128-1.626.064-2.004.183-1.69.535-2.737 1.427-3.388 3.032-.222.546-.344 1.1-.383 1.868l-.03.276c.13.686.144 1.14.413 1.653.132.252.447.451.5.765.032.185-.104.464-.147.613-.065.224-.041.48-.147.673-.192.349-.714.087-.943-.061-1.192-.77-2.175-2.995-1.62-5.144.085-.332.09-.62.206-.919.723-1.844 1.802-2.978 3.359-3.95.583-.364 1.37-.544 2.092-.734l1.149-.154Z"/></svg>
                    </a>
                </li>
                <li>
                    <a href="#" class="footer-social" aria-label="YouTube">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24"><path fill-rule="evenodd" clip-rule="evenodd" d="M20.44 5.243c.929.244 1.66.963 1.909 1.876.451 1.654.451 5.106.451 5.106s0 3.452-.451 5.106a2.681 2.681 0 0 1-1.91 1.876c-1.684.443-8.439.443-8.439.443s-6.754 0-8.439-.443a2.682 2.682 0 0 1-1.91-1.876c-.45-1.654-.45-5.106-.45-5.106s0-3.452.45-5.106a2.681 2.681 0 0 1 1.91-1.876c1.685-.443 8.44-.443 8.44-.443s6.754 0 8.438.443Zm-5.004 6.982L9.792 15.36V9.091l5.646 3.134Z"/></svg>
                    </a>
                </li>
                <li>
                    <a href="#" class="footer-social" aria-label="TikTok">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24"><path d="M20.027 10.168a5.125 5.125 0 0 1-4.76-2.294v7.893a5.833 5.833 0 1 1-5.834-5.834c.122 0 .241.011.361.019v2.874c-.12-.014-.237-.036-.36-.036a2.977 2.977 0 0 0 0 5.954c1.644 0 3.096-1.295 3.096-2.94L12.56 2.4h2.75a5.122 5.122 0 0 0 4.72 4.573v3.195"/></svg>
                    </a>
                </li>
                <li>
                    <a href="#" class="footer-social" aria-label="LinkedIn">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24"><path fill-rule="evenodd" clip-rule="evenodd" d="M7.349 5.478a1.875 1.875 0 1 0-3.749 0 1.875 1.875 0 1 0 3.749 0ZM7.092 19.2H3.857V8.78h3.235V19.2ZM12.22 8.78H9.121V19.2h3.228v-5.154c0-1.36.257-2.676 1.94-2.676 1.658 0 1.68 1.554 1.68 2.763V19.2H19.2v-5.715c0-2.806-.605-4.963-3.877-4.963-1.573 0-2.629.863-3.06 1.683h-.044V8.78Z"/></svg>
                    </a>
                </li>
            </ul>
        </div>

        <div class="footer-col">
            <h6 class="footer-heading">Explore</h6>
            <ul class="footer-nav">
                <li><?= $this->Html->link('Jewellery', ['controller' => 'Jewelry', 'action' => 'index']) ?></li>
                <li><?= $this->Html->link('Home Décor', ['controller' => 'Jewelry', 'action' => 'home_decor']) ?></li>
                <li><?= $this->Html->link('Our Story', ['controller' => 'OurStory', 'action' => 'index']) ?></li>
                <li><?= $this->Html->link('Location', ['controller' => 'Pages', 'action' => 'location']) ?></li>
            </ul>
        </div>

        <div class="footer-col">
            <h6 class="footer-heading">Services</h6>
            <ul class="footer-nav">
                <li><?= $this->Html->link('FAQ', ['controller' => 'Faq', 'action' => 'index']) ?></li>
                <li><?= $this->Html->link('Contact Us', ['controller' => 'ContactSubmissions', 'action' => 'add']) ?></li>
            </ul>
        </div>

        <div class="footer-col footer-col--newsletter">
            <h6 class="footer-heading">Newsletter</h6>
            <p class="footer-newsletter-text">Subscribe for early access to new collections and exclusive offers.</p>
            <form class="footer-newsletter-form" action="#" method="post" onsubmit="footerNewsletterSubmit(event)">
                <input type="email" name="email" placeholder="Your email address" class="footer-newsletter-input" required>
                <button type="submit" class="footer-newsletter-btn">Subscribe</button>
            </form>
            <p class="footer-contact-detail" id="newsletterThanks" style="display:none">Thanks for subscribing!</p>
            <p class="footer-newsletter-disclaimer">No spam. Unsubscribe anytime.</p>
            <p class="footer-contact-detail">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                : 10:00AM – 6:00PM
            </p>
            <p class="footer-contact-detail">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 10a19.79 19.79 0 01-3-8.57A2 2 0 012 1.28h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 9.09a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                : 123 456 7890
            </p>
            <p class="footer-contact-detail">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                : 88 Elizabeth Road, Melbourne VIC 3000
            </p>
        </div>

    </div>

    <div class="footer-bottom">
        <div class="footer-bottom-inner">
            <div class="footer-bottom-left">
                <span class="footer-copyright">© <?= date('Y') ?> Veloura Jewels</span>
                <nav class="footer-legal">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms &amp; Conditions</a>
                    <a href="#">Refund Policy</a>
                    <a href="#">Shipping Policy</a>
                </nav>
            </div>
            <div class="footer-payments"><svg xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="pi-american_express" viewBox="0 0 38 24" width="38" height="24"><title id="pi-american_express">American Express</title><path fill="#000" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3Z" opacity=".07"/><path fill="#006FCF" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32Z"/><path fill="#FFF" d="M22.012 19.936v-8.421L37 11.528v2.326l-1.732 1.852L37 17.573v2.375h-2.766l-1.47-1.622-1.46 1.628-9.292-.02Z"/><path fill="#006FCF" d="M23.013 19.012v-6.57h5.572v1.513h-3.768v1.028h3.678v1.488h-3.678v1.01h3.768v1.531h-5.572Z"/><path fill="#006FCF" d="m28.557 19.012 3.083-3.289-3.083-3.282h2.386l1.884 2.083 1.89-2.082H37v.051l-3.017 3.23L37 18.92v.093h-2.307l-1.917-2.103-1.898 2.104h-2.321Z"/><path fill="#FFF" d="M22.71 4.04h3.614l1.269 2.881V4.04h4.46l.77 2.159.771-2.159H37v8.421H19l3.71-8.421Z"/><path fill="#006FCF" d="m23.395 4.955-2.916 6.566h2l.55-1.315h2.98l.55 1.315h2.05l-2.904-6.566h-2.31Zm.25 3.777.875-2.09.873 2.09h-1.748Z"/><path fill="#006FCF" d="M28.581 11.52V4.953l2.811.01L32.84 9l1.456-4.046H37v6.565l-1.74.016v-4.51l-1.644 4.494h-1.59L30.35 7.01v4.51h-1.768Z"/></svg>
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" role="img" x="0" y="0" width="38" height="24" viewBox="0 0 165.521 105.965" xml:space="preserve" aria-labelledby="pi-apple_pay"><title id="pi-apple_pay">Apple Pay</title><path fill="#000" d="M150.698 0H14.823c-.566 0-1.133 0-1.698.003-.477.004-.953.009-1.43.022-1.039.028-2.087.09-3.113.274a10.51 10.51 0 0 0-2.958.975 9.932 9.932 0 0 0-4.35 4.35 10.463 10.463 0 0 0-.975 2.96C.113 9.611.052 10.658.024 11.696a70.22 70.22 0 0 0-.022 1.43C0 13.69 0 14.256 0 14.823v76.318c0 .567 0 1.132.002 1.699.003.476.009.953.022 1.43.028 1.036.09 2.084.275 3.11a10.46 10.46 0 0 0 .974 2.96 9.897 9.897 0 0 0 1.83 2.52 9.874 9.874 0 0 0 2.52 1.83c.947.483 1.917.79 2.96.977 1.025.183 2.073.245 3.112.273.477.011.953.017 1.43.02.565.004 1.132.004 1.698.004h135.875c.565 0 1.132 0 1.697-.004.476-.002.952-.009 1.431-.02 1.037-.028 2.085-.09 3.113-.273a10.478 10.478 0 0 0 2.958-.977 9.955 9.955 0 0 0 4.35-4.35c.483-.947.789-1.917.974-2.96.186-1.026.246-2.074.274-3.11.013-.477.02-.954.022-1.43.004-.567.004-1.132.004-1.699V14.824c0-.567 0-1.133-.004-1.699a63.067 63.067 0 0 0-.022-1.429c-.028-1.038-.088-2.085-.274-3.112a10.4 10.4 0 0 0-.974-2.96 9.94 9.94 0 0 0-4.35-4.35A10.52 10.52 0 0 0 156.939.3c-1.028-.185-2.076-.246-3.113-.274a71.417 71.417 0 0 0-1.431-.022C151.83 0 151.263 0 150.698 0z" /><path fill="#FFF" d="M150.698 3.532l1.672.003c.452.003.905.008 1.36.02.793.022 1.719.065 2.583.22.75.135 1.38.34 1.984.648a6.392 6.392 0 0 1 2.804 2.807c.306.6.51 1.226.645 1.983.154.854.197 1.783.218 2.58.013.45.019.9.02 1.36.005.557.005 1.113.005 1.671v76.318c0 .558 0 1.114-.004 1.682-.002.45-.008.9-.02 1.35-.022.796-.065 1.725-.221 2.589a6.855 6.855 0 0 1-.645 1.975 6.397 6.397 0 0 1-2.808 2.807c-.6.306-1.228.511-1.971.645-.881.157-1.847.2-2.574.22-.457.01-.912.017-1.379.019-.555.004-1.113.004-1.669.004H14.801c-.55 0-1.1 0-1.66-.004a74.993 74.993 0 0 1-1.35-.018c-.744-.02-1.71-.064-2.584-.22a6.938 6.938 0 0 1-1.986-.65 6.337 6.337 0 0 1-1.622-1.18 6.355 6.355 0 0 1-1.178-1.623 6.935 6.935 0 0 1-.646-1.985c-.156-.863-.2-1.788-.22-2.578a66.088 66.088 0 0 1-.02-1.355l-.003-1.327V14.474l.002-1.325a66.7 66.7 0 0 1 .02-1.357c.022-.792.065-1.717.222-2.587a6.924 6.924 0 0 1 .646-1.981c.304-.598.7-1.144 1.18-1.623a6.386 6.386 0 0 1 1.624-1.18 6.96 6.96 0 0 1 1.98-.646c.865-.155 1.792-.198 2.586-.22.452-.012.905-.017 1.354-.02l1.677-.003h135.875" /><g><g><path fill="#000" d="M43.508 35.77c1.404-1.755 2.356-4.112 2.105-6.52-2.054.102-4.56 1.355-6.012 3.112-1.303 1.504-2.456 3.959-2.156 6.266 2.306.2 4.61-1.152 6.063-2.858" /><path fill="#000" d="M45.587 39.079c-3.35-.2-6.196 1.9-7.795 1.9-1.6 0-4.049-1.8-6.698-1.751-3.447.05-6.645 2-8.395 5.1-3.598 6.2-.95 15.4 2.55 20.45 1.699 2.5 3.747 5.25 6.445 5.151 2.55-.1 3.549-1.65 6.647-1.65 3.097 0 3.997 1.65 6.696 1.6 2.798-.05 4.548-2.5 6.247-5 1.95-2.85 2.747-5.6 2.797-5.75-.05-.05-5.396-2.101-5.446-8.251-.05-5.15 4.198-7.6 4.398-7.751-2.399-3.548-6.147-3.948-7.447-4.048" /></g><g><path fill="#000" d="M78.973 32.11c7.278 0 12.347 5.017 12.347 12.321 0 7.33-5.173 12.373-12.529 12.373h-8.058V69.62h-5.822V32.11h14.062zm-8.24 19.807h6.68c5.07 0 7.954-2.729 7.954-7.46 0-4.73-2.885-7.434-7.928-7.434h-6.706v14.894z" /><path fill="#000" d="M92.764 61.847c0-4.809 3.665-7.564 10.423-7.98l7.252-.442v-2.08c0-3.04-2.001-4.704-5.562-4.704-2.938 0-5.07 1.507-5.51 3.82h-5.252c.157-4.86 4.731-8.395 10.918-8.395 6.654 0 10.995 3.483 10.995 8.89v18.663h-5.38v-4.497h-.13c-1.534 2.937-4.914 4.782-8.579 4.782-5.406 0-9.175-3.222-9.175-8.057zm17.675-2.417v-2.106l-6.472.416c-3.64.234-5.536 1.585-5.536 3.95 0 2.288 1.975 3.77 5.068 3.77 3.95 0 6.94-2.522 6.94-6.03z" /><path fill="#000" d="M120.975 79.652v-4.496c.364.051 1.247.103 1.715.103 2.573 0 4.029-1.09 4.913-3.899l.52-1.663-9.852-27.293h6.082l6.863 22.146h.13l6.862-22.146h5.927l-10.216 28.67c-2.34 6.577-5.017 8.735-10.683 8.735-.442 0-1.872-.052-2.261-.157z" /></g></g></svg>
                <svg xmlns="http://www.w3.org/2000/svg" aria-labelledby="pi-bancontact" role="img" viewBox="0 0 38 24" width="38" height="24"><title id="pi-bancontact">Bancontact</title><path fill="#000" opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"/><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"/><path d="M4.703 3.077h28.594c.139 0 .276.023.405.068.128.045.244.11.343.194a.9.9 0 0 1 .229.29c.053.107.08.223.08.34V20.03a.829.829 0 0 1-.31.631 1.164 1.164 0 0 1-.747.262H4.703a1.23 1.23 0 0 1-.405-.068 1.09 1.09 0 0 1-.343-.194.9.9 0 0 1-.229-.29.773.773 0 0 1-.08-.34V3.97c0-.118.027-.234.08-.342a.899.899 0 0 1 .23-.29c.098-.082.214-.148.342-.193a1.23 1.23 0 0 1 .405-.068Z" fill="#fff"/><path d="M6.38 18.562v-3.077h1.125c.818 0 1.344.259 1.344.795 0 .304-.167.515-.401.638.338.132.536.387.536.734 0 .62-.536.91-1.37.91H6.38Zm.724-1.798h.537c.328 0 .468-.136.468-.387 0-.268-.255-.356-.599-.356h-.406v.743Zm0 1.262h.448c.438 0 .693-.093.693-.383 0-.286-.219-.404-.63-.404h-.51v.787Zm3.284.589c-.713 0-1.073-.295-1.073-.69 0-.436.422-.69 1.047-.695.156.002.31.014.464.035v-.105c0-.269-.183-.396-.531-.396a2.128 2.128 0 0 0-.688.105l-.13-.474a3.01 3.01 0 0 1 .9-.132c.767 0 1.147.343 1.147.936v1.222c-.214.093-.615.194-1.136.194Zm.438-.497v-.47a2.06 2.06 0 0 0-.37-.036c-.24 0-.427.08-.427.286 0 .185.156.281.432.281a.947.947 0 0 0 .365-.061Zm1.204.444v-2.106a3.699 3.699 0 0 1 1.177-.193c.76 0 1.198.316 1.198.9v1.399h-.719v-1.354c0-.303-.167-.444-.484-.444a1.267 1.267 0 0 0-.459.079v1.719h-.713Zm4.886-2.167-.135.479a1.834 1.834 0 0 0-.588-.11c-.422 0-.652.25-.652.664 0 .453.24.685.688.685.2-.004.397-.043.578-.114l.115.488a2.035 2.035 0 0 1-.75.128c-.865 0-1.365-.453-1.365-1.17 0-.712.495-1.182 1.323-1.182.27-.001.538.043.787.132Zm1.553 2.22c-.802 0-1.302-.47-1.302-1.178 0-.704.5-1.174 1.302-1.174.807 0 1.297.47 1.297 1.173 0 .708-.49 1.179-1.297 1.179Zm0-.502c.37 0 .563-.259.563-.677 0-.413-.193-.672-.563-.672-.364 0-.568.26-.568.672 0 .418.204.677.568.677Zm1.713.449v-2.106a3.699 3.699 0 0 1 1.177-.193c.76 0 1.198.316 1.198.9v1.399h-.719v-1.354c0-.303-.166-.444-.484-.444a1.268 1.268 0 0 0-.459.079v1.719h-.713Zm3.996.053c-.62 0-.938-.286-.938-.866v-.95h-.354v-.484h.355v-.488l.718-.03v.518h.578v.484h-.578v.94c0 .256.125.374.36.374.093 0 .185-.008.276-.026l.036.488c-.149.028-.3.041-.453.04Zm1.814 0c-.713 0-1.073-.295-1.073-.69 0-.436.422-.69 1.047-.695.155.002.31.014.464.035v-.105c0-.269-.183-.396-.532-.396a2.128 2.128 0 0 0-.687.105l-.13-.474a3.01 3.01 0 0 1 .9-.132c.766 0 1.146.343 1.146.936v1.222c-.213.093-.614.194-1.135.194Zm.438-.497v-.47a2.06 2.06 0 0 0-.37-.036c-.24 0-.427.08-.427.286 0 .185.156.281.432.281a.946.946 0 0 0 .365-.061Zm3.157-1.723-.136.479a1.834 1.834 0 0 0-.588-.11c-.422 0-.651.25-.651.664 0 .453.24.685.687.685.2-.004.397-.043.578-.114l.115.488a2.035 2.035 0 0 1-.75.128c-.865 0-1.365-.453-1.365-1.17 0-.712.495-1.182 1.323-1.182.27-.001.538.043.787.132Zm1.58 2.22c-.62 0-.938-.286-.938-.866v-.95h-.354v-.484h.354v-.488l.72-.03v.518h.577v.484h-.578v.94c0 .256.125.374.36.374.092 0 .185-.008.276-.026l.036.488c-.149.028-.3.041-.453.04Z" fill="#1E3764"/><path d="M11.394 13.946c3.803 0 5.705-2.14 7.606-4.28H6.38v4.28h5.014Z" fill="url(#pi-bancontact-a)"/><path d="M26.607 5.385c-3.804 0-5.705 2.14-7.607 4.28h12.62v-4.28h-5.013Z" fill="url(#pi-bancontact-b)"/><defs><linearGradient id="pi-bancontact-a" x1="8.933" y1="12.003" x2="17.734" y2="8.13" gradientUnits="userSpaceOnUse"><stop stop-color="#005AB9"/><stop offset="1" stop-color="#1E3764"/></linearGradient><linearGradient id="pi-bancontact-b" x1="19.764" y1="10.037" x2="29.171" y2="6.235" gradientUnits="userSpaceOnUse"><stop stop-color="#FBA900"/><stop offset="1" stop-color="#FFD800"/></linearGradient></defs></svg><svg xmlns="http://www.w3.org/2000/svg" role="img" viewBox="0 0 38 24" width="38" height="24" aria-labelledby="pi-blik"><title id="pi-blik">BLIK</title><path fill="#000" opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"/><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"/><path d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32z" fill="url(#pi-blik-paint0_linear)"/><path d="M30.343 17.155l-2.785-3.639 2.563-3.236h-2.185l-2.456 3.138V6.78h-1.848v10.375h1.848v-3.532l2.456 3.532h2.407zM18.613 6.78h-1.848v10.366h1.848V6.78zm3.433 3.508h-1.848v6.867h1.848v-6.867z" fill="#fff"/><path d="M13.849 9.573a1.651 1.651 0 100-3.302 1.651 1.651 0 000 3.302z" fill="url(#pi-blik-paint1_radial)"/><path d="M12.041 10.206c-.574 0-1.138.144-1.642.419V6.82H8.534v6.9a3.516 3.516 0 103.507-3.515zm0 5.175a1.643 1.643 0 110-3.286 1.643 1.643 0 010 3.286z" fill="#fff"/><defs><radialGradient id="pi-blik-paint1_radial" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(12.51 6.18) scale(5.41297)"><stop stop-color="red"/><stop offset=".49" stop-color="#E83E49"/><stop offset="1" stop-color="#F0F"/></radialGradient><linearGradient id="pi-blik-paint0_linear" x1="19" y1="4.977" x2="19" y2="55.605" gradientUnits="userSpaceOnUse"><stop/><stop offset=".732" stop-color="#fff"/><stop offset="1" stop-color="#fff"/></linearGradient></defs></svg><svg width="38" height="24" role="img" viewBox="0 0 38 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-labelledby="pi-cartes_bancaires"><title id="pi-cartes_bancaires">Cartes Bancaires</title><rect x="1" y="1" width="36" height="22" rx="2" fill="url(#pi-cartes_bancaires-paint0_linear)"/><rect x=".5" y=".5" width="37" height="23" rx="2.5" stroke="#000" stroke-opacity=".07"/><path fill-rule="evenodd" clip-rule="evenodd" d="M28 9.934c0 1.067-.8 1.932-1.79 1.934v.002h-6.52V8h6.52c.99.002 1.79.867 1.79 1.934zm0 4.104c0 1.067-.8 1.932-1.79 1.934v.003h-6.52v-3.87h6.52c.99.002 1.79.867 1.79 1.933zm-13.224-1.934h4.788v.378c0 1.943-1.46 3.518-3.26 3.518H13.26C11.46 16 10 14.425 10 12.482v-.938c0-1.943 1.46-3.518 3.26-3.518h3.044c1.8 0 3.26 1.575 3.26 3.518v.326h-4.788v.234z" fill="#fff"/><defs><linearGradient id="pi-cartes_bancaires-paint0_linear" x1="37" y1="1" x2="17.422" y2="33.036" gradientUnits="userSpaceOnUse"><stop stop-color="#083969"/><stop offset=".492" stop-color="#007B9D"/><stop offset="1" stop-color="#00A84A"/></linearGradient></defs></svg><svg xmlns="http://www.w3.org/2000/svg" role="img" viewBox="0 0 38 24" width="38" height="24" aria-labelledby="pi-eps"> <title id="pi-eps">EPS</title> <path d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z" opacity=".07"/> <path d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32" fill="#fff"/> <path fill="#71706f" d="M27.745 12.32h-2.322a.465.465 0 01-.468-.464c0-.258.21-.484.468-.484h3.535V9.628h-3.535c-1.233 0-2.237 1.006-2.237 2.236s1.004 2.237 2.237 2.237h2.29c.259 0 .469.205.469.462 0 .258-.21.448-.468.448h-4.912c-.417.796-.822 1.478-1.645 1.82h6.588c1.213-.018 2.205-1.045 2.205-2.265 0-1.22-.992-2.23-2.205-2.247z"/> <path fill="#71706f" d="M18.845 9.628c-1.968 0-3.571 1.612-3.571 3.594V20.697h1.782V16.83h1.785c1.968 0 3.565-1.634 3.565-3.615 0-1.98-1.593-3.587-3.561-3.587zm0 5.383h-1.79v-1.796c0-1.003.8-1.82 1.79-1.82s1.796.817 1.796 1.82a1.79 1.79 0 01-1.796 1.796z"/> <path fill="#c8036f" d="M9.634 16.83c-1.685 0-3.101-1.2-3.484-2.768 0 0-.111-.519-.111-.86 0-.342.105-.865.105-.865a3.594 3.594 0 013.482-2.73c1.978 0 3.6 1.608 3.6 3.586v.87H7.973c.31.607.938.948 1.662.948h4.724l.006-5.13c0-.764-.625-1.39-1.39-1.39H6.278c-.764 0-1.39.607-1.39 1.371v6.696c0 .765.626 1.41 1.39 1.41h6.696c.686 0 1.259-.493 1.37-1.138h-4.71z"/> <path fill="#c8036f" d="M9.626 11.31c-.72 0-1.348.44-1.66 1.01h3.32c-.312-.57-.939-1.01-1.66-1.01zM12.659 6.314c0-1.635-1.359-2.96-3.034-2.96-1.647 0-2.987 1.282-3.031 2.879v.91c0 .106.086.21.195.21h1.116c.11 0 .205-.104.205-.21v-.83c0-.815.68-1.48 1.516-1.48.837 0 1.516.665 1.516 1.48v.83c0 .106.089.21.198.21h1.116c.109 0 .202-.104.202-.21v-.83z"/> <g> <path fill="#71706f" d="M20.843 19.142l-.16.914c-.082.462-.384.673-.794.673-.347 0-.616-.226-.538-.672l.161-.914h.249l-.161.913c-.05.285.078.447.32.447.249 0 .462-.148.513-.446l.161-.915h.25zm-.977-.285c0 .076.059.112.121.112.079 0 .164-.057.164-.162 0-.074-.05-.112-.114-.112-.08 0-.17.052-.17.162zm.538-.003c0 .079.06.114.126.114.07 0 .161-.056.161-.159 0-.074-.054-.114-.114-.114-.08 0-.173.052-.173.16zM21.445 19.142l-.112.632a.57.57 0 01.423-.199c.278 0 .47.163.47.465 0 .428-.297.686-.678.686-.148 0-.274-.055-.35-.194l-.042.165h-.218l.276-1.554h.23zm-.175 1.066c-.01.183.11.302.282.302a.444.444 0 00.441-.423c.01-.186-.111-.296-.28-.296a.45.45 0 00-.443.417zM22.68 20.242c-.01.151.11.275.311.275.112 0 .26-.044.34-.12l.126.145a.782.782 0 01-.508.188c-.319 0-.505-.19-.505-.482 0-.388.305-.677.695-.677.356 0 .558.216.437.67h-.896zm.708-.19c.018-.19-.094-.267-.28-.267-.168 0-.323.078-.39.267h.67zM24.193 19.597l-.012.146a.414.414 0 01.357-.17c.107 0 .204.039.255.095l-.139.201a.244.244 0 00-.183-.067.346.346 0 00-.352.3l-.106.595h-.23l.195-1.1h.215zM25.905 19.597l.116.87.422-.87h.257l-.578 1.1h-.27l-.11-.725-.178.36-.192.365h-.27l-.194-1.1h.26l.114.87.424-.87h.199zM26.997 20.242c-.01.151.11.275.311.275.112 0 .26-.044.34-.12l.126.145a.782.782 0 01-.508.188c-.319 0-.505-.19-.505-.482 0-.388.305-.677.695-.677.356 0 .558.216.437.67h-.896zm.708-.19c.018-.19-.094-.267-.28-.267-.168 0-.323.078-.39.267h.67zM28.525 19.597l-.194 1.1H28.1l.195-1.1h.23zm-.204-.3c0 .08.059.12.121.12.09 0 .173-.059.173-.168a.113.113 0 00-.117-.117c-.082 0-.177.054-.177.166zM29.55 19.87c-.072-.081-.164-.104-.274-.104-.152 0-.26.052-.26.148 0 .08.088.114.218.126.201.017.459.085.405.37-.038.205-.244.326-.53.326-.18 0-.353-.04-.47-.204l.151-.163c.083.114.229.161.358.164.11 0 .24-.04.26-.144.02-.098-.067-.136-.228-.152-.188-.018-.394-.087-.394-.296 0-.276.298-.372.522-.372.17 0 .295.038.399.148l-.157.152zM30.278 19.597l-.105.592c-.034.189.051.319.244.319.181 0 .336-.15.367-.334l.101-.576h.231l-.195 1.099h-.208l.015-.16a.573.573 0 01-.412.182c-.271 0-.432-.194-.374-.528l.105-.593h.231zM32.13 20.697l.105-.589c.033-.19-.032-.317-.242-.317-.184 0-.339.148-.37.33l-.1.576h-.232l.195-1.1h.21l-.015.16a.583.583 0 01.403-.176c.27 0 .444.187.384.525l-.106.59h-.233zM32.912 20.848c-.016.152.1.213.296.213.17 0 .354-.095.396-.337l.034-.189c-.1.142-.285.197-.421.197-.283 0-.48-.168-.48-.473 0-.434.323-.685.688-.685.155 0 .294.073.343.199l.038-.176h.224l-.193 1.132c-.071.42-.383.55-.668.55-.34 0-.528-.165-.481-.43h.224zm.065-.633c0 .188.123.302.3.302.477 0 .605-.733.128-.733a.42.42 0 00-.428.43z"/></g></svg><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 38 24" width="38" height="24" role="img" aria-labelledby="pi-spidealwero"><title id="pi-spidealwero">iDEAL Wero</title><path opacity="0.07" d="M35 0H3C1.3 0 0 1.3 0 3V21C0 22.7 1.4 24 3 24H35C36.7 24 38 22.7 38 21V3C38 1.3 36.6 0 35 0Z" fill="black"/><path d="M35 1C36.1 1 37 1.9 37 3V21C37 22.1 36.1 23 35 23H3C1.9 23 1 22.1 1 21V3C1 1.9 1.9 1 3 1H35Z" fill="#FFF48D"/><path d="M3.45001 7.43572V17.0022C3.45001 17.4602 3.82664 17.8347 4.28673 17.8347H10.0301C14.3722 17.8347 16.2539 15.4157 16.2539 12.2063C16.2539 8.99693 14.3715 6.60321 10.0294 6.60321H4.28603C3.82594 6.60321 3.44931 6.97773 3.44931 7.43572H3.45001Z" fill="white"/><path d="M7.29413 8.95133V16.0203H10.385C13.1918 16.0203 14.4087 14.4422 14.4087 12.2112C14.4087 9.98022 13.1911 8.41901 10.385 8.41901H7.82926C7.53259 8.41901 7.29413 8.66027 7.29413 8.95204V8.95133Z" fill="#CC0066"/><path fill-rule="evenodd" clip-rule="evenodd" d="M5.03507 17.073H10.0294C13.539 17.073 15.4754 15.3456 15.4754 12.2105C15.4754 10.4038 14.7685 7.36908 10.0294 7.36908H5.03507C4.59182 7.36908 4.23203 7.72677 4.23203 8.16862V16.2742C4.23203 16.7153 4.59182 17.0737 5.03507 17.0737V17.073ZM4.49924 8.16862C4.49924 7.87335 4.7377 7.63559 5.03437 7.63559H10.0287C11.9609 7.63559 15.2068 8.23104 15.2068 12.2105C15.2068 15.1744 13.3665 16.8065 10.0287 16.8065H5.03507C4.7384 16.8065 4.49994 16.5694 4.49994 16.2735V8.16862H4.49924Z" fill="#232323"/><path fill-rule="evenodd" clip-rule="evenodd" d="M8.81464 11.1831C8.72066 11.1494 8.62247 11.1326 8.51586 11.1326V11.1284H7.8082V12.8355H8.52428C8.65122 12.8355 8.76134 12.8102 8.85602 12.7681C8.95 12.7218 9.02785 12.6629 9.08957 12.5872C9.15129 12.5114 9.19618 12.4188 9.22844 12.3136C9.25719 12.2084 9.27332 12.0948 9.27332 11.9686C9.27332 11.8255 9.25299 11.7035 9.21581 11.5983C9.17514 11.4973 9.12183 11.4089 9.05661 11.3374C8.98717 11.27 8.90932 11.2153 8.81534 11.1817L8.81464 11.1831ZM8.64702 12.4995C8.59371 12.5163 8.54462 12.5248 8.49132 12.5248V12.529H8.16799V11.4524H8.4296C8.51937 11.4524 8.59301 11.465 8.65473 11.4903C8.71645 11.5155 8.76554 11.5576 8.80201 11.6039C8.83849 11.6502 8.86724 11.7133 8.88407 11.7806C8.9002 11.8479 8.90862 11.9279 8.90862 12.0121C8.90862 12.1088 8.896 12.1846 8.87215 12.2519C8.85108 12.3106 8.81921 12.3649 8.77817 12.4118C8.74305 12.4524 8.69779 12.4829 8.64702 12.5002V12.4995Z" fill="white"/><path d="M10.7504 11.1333V11.4489H9.87441V11.815H10.6803V12.1053H9.87441V12.5219H10.7707V12.8376H9.51041V11.1305H10.7504V11.1347V11.1333Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M12.6244 12.8404L12.0023 11.1333H11.6222L10.9958 12.8404H11.3641L11.4952 12.4616H12.1173L12.2443 12.8404H12.6251H12.6244ZM11.8136 11.5534L12.0226 12.1797H11.5934L11.8101 11.5534H11.8143H11.8136Z" fill="white"/><path d="M13.2178 11.1325V12.5247H14.0278V12.8403H12.8538V11.1332H13.2178V11.1325Z" fill="white"/><path d="M5.85774 12.8656C6.30099 12.8656 6.66009 12.5065 6.66009 12.0625C6.66009 11.6186 6.30099 11.2595 5.85774 11.2595C5.41448 11.2595 5.05539 11.6186 5.05539 12.0625C5.05539 12.5065 5.41448 12.8656 5.85774 12.8656ZM6.33536 16.0771C5.71396 16.0771 5.216 15.5406 5.216 14.8813V13.9478C5.216 13.6181 5.46498 13.3474 5.77778 13.3474C6.09059 13.3474 6.33957 13.6139 6.33957 13.9478V16.0771H6.33536Z" fill="#232323"/><path d="M31.45 12.2209C31.45 11.1773 32.1926 10.2264 33.4749 10.2264C34.7572 10.2264 35.5053 11.178 35.5053 12.2209C35.5053 13.2638 34.7627 14.2154 33.4749 14.2154C32.1926 14.2154 31.45 13.2638 31.45 12.2209ZM34.3782 12.2209C34.3782 11.7178 34.0467 11.2537 33.4749 11.2537C32.9032 11.2537 32.5717 11.7186 32.5717 12.2209C32.5717 12.7232 32.9086 13.1889 33.4749 13.1889C34.0412 13.1889 34.3782 12.724 34.3782 12.2209ZM30.6615 12.9619C31.0889 12.7185 31.3455 12.2537 31.3455 11.7404C31.3455 10.9784 30.7948 10.3294 29.94 10.3294H28.1452V14.1132H29.2567V13.146H29.4649L30.1006 14.1132H31.4095L30.6615 12.9619ZM29.7161 12.2747H29.2567V11.2046H29.7216C30.0211 11.2046 30.213 11.4479 30.213 11.7396C30.213 12.0314 30.0148 12.2747 29.7161 12.2747ZM23.0713 10.3247L22.4044 12.5797L21.7539 10.3247H20.8686L20.2126 12.5797L19.5511 10.3247H18.378L19.722 14.1015H20.6868L21.3108 12.0454L21.9294 14.1015H22.8997L24.2436 10.3247H23.0705H23.0713ZM25.9261 13.1795H25.9237C25.508 13.1795 25.2163 12.9284 25.0915 12.5961H27.9159C27.9385 12.4713 27.9502 12.3434 27.9502 12.2139C27.9502 11.1734 27.21 10.2241 25.9261 10.2233V11.2482C26.3442 11.249 26.6328 11.5002 26.7568 11.8317H23.9363C23.9137 11.9565 23.902 12.0844 23.902 12.2139C23.902 13.2552 24.643 14.2052 25.923 14.2052H25.9253V13.1795H25.9261Z" fill="#1D1C1C"/><path d="M25.9237 14.2044C25.9674 14.2044 26.0103 14.2029 26.0524 14.2013C26.3122 14.188 26.5485 14.135 26.7591 14.05C26.9697 13.965 27.1546 13.8472 27.3114 13.7052C27.4673 13.5644 27.5983 13.3982 27.699 13.2138C27.7903 13.0469 27.8574 12.8667 27.8995 12.6787H26.7225C26.6983 12.7287 26.6702 12.777 26.6382 12.8215C26.5945 12.8831 26.5431 12.9385 26.4846 12.9861C26.4261 13.0344 26.3605 13.075 26.2888 13.1062C26.217 13.1374 26.1382 13.1592 26.0532 13.1709C26.0119 13.1764 25.969 13.1795 25.9245 13.1795C25.6601 13.1795 25.4456 13.0773 25.2919 12.9174L24.5268 13.6912C24.8668 14.0055 25.3387 14.2036 25.9237 14.2036V14.2044Z" fill="#1D1C1C"/><path d="M25.9237 10.2225C24.838 10.2225 24.1399 10.9058 23.9535 11.7482H25.1266C25.267 11.4581 25.5431 11.2475 25.9237 11.2475C26.2272 11.2475 26.4627 11.3785 26.6164 11.5766L27.3886 10.7958C27.0438 10.4464 26.5477 10.2225 25.9245 10.2225H25.9237Z" fill="#1D1C1C"/><path d="M17.3927 16.431C17.3069 16.431 17.2367 16.3608 17.2367 16.275V8.16296C17.2367 8.07716 17.3069 8.00696 17.3927 8.00696C17.4785 8.00696 17.5487 8.07716 17.5487 8.16296V16.275C17.5487 16.3608 17.4785 16.431 17.3927 16.431Z" fill="#1D1C1C"/></svg><svg xmlns="http://www.w3.org/2000/svg" role="img" width="38" height="24" viewBox="0 0 38 24" aria-labelledby="pi-klarna" fill="none"><title id="pi-klarna">Klarna</title><rect width="38" height="24" rx="2" fill="#FFA8CD"/><rect x=".5" y=".5" width="37" height="23" rx="1.5" stroke="#000" stroke-opacity=".07"/><path d="M30.62 14.755c-.662 0-1.179-.554-1.179-1.226 0-.673.517-1.226 1.18-1.226.663 0 1.18.553 1.18 1.226 0 .672-.517 1.226-1.18 1.226zm-.33 1.295c.565 0 1.286-.217 1.686-1.068l.04.02c-.176.465-.176.742-.176.81v.11h1.423v-4.786H31.84v.109c0 .069 0 .346.175.81l-.039.02c-.4-.85-1.121-1.068-1.687-1.068-1.355 0-2.31 1.088-2.31 2.522 0 1.433.955 2.521 2.31 2.521zm-4.788-5.043c-.643 0-1.15.228-1.56 1.068l-.039-.02c.175-.464.175-.741.175-.81v-.11h-1.423v4.787h1.462V13.4c0-.662.38-1.078.995-1.078.614 0 .917.356.917 1.068v2.532h1.462v-3.046c0-1.088-.838-1.869-1.989-1.869zm-4.963 1.068l-.039-.02c.176-.464.176-.741.176-.81v-.11h-1.424v4.787h1.463l.01-2.304c0-.673.35-1.078.926-1.078.156 0 .282.02.429.06v-1.464c-.644-.139-1.22.109-1.54.94zm-4.65 2.68c-.664 0-1.18-.554-1.18-1.226 0-.673.516-1.226 1.18-1.226.662 0 1.179.553 1.179 1.226 0 .672-.517 1.226-1.18 1.226zm-.332 1.295c.565 0 1.287-.217 1.687-1.068l.038.02c-.175.465-.175.742-.175.81v.11h1.424v-4.786h-1.424v.109c0 .069 0 .346.175.81l-.038.02c-.4-.85-1.122-1.068-1.687-1.068-1.356 0-2.311 1.088-2.311 2.522 0 1.433.955 2.521 2.31 2.521zm-4.349-.128h1.463V9h-1.463v6.922zM10.136 9H8.644c0 1.236-.751 2.343-1.892 3.134l-.448.317V9h-1.55v6.922h1.55V12.49l2.564 3.43h1.892L8.293 12.64c1.121-.82 1.852-2.096 1.843-3.639z" fill="#0B051D"/></svg><svg viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" role="img" width="38" height="24" aria-labelledby="pi-master"><title id="pi-master">Mastercard</title><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"/><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"/><circle fill="#EB001B" cx="15" cy="12" r="7"/><circle fill="#F79E1B" cx="23" cy="12" r="7"/><path fill="#FF5F00" d="M22 12c0-2.4-1.2-4.5-3-5.7-1.8 1.3-3 3.4-3 5.7s1.2 4.5 3 5.7c1.8-1.2 3-3.3 3-5.7z"/></svg><svg viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" width="38" height="24" role="img" aria-labelledby="pi-mobilepay"><title id="pi-mobilepay">MobilePay</title><path fill="#000" opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"/><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"/><path fill-rule="evenodd" clip-rule="evenodd" d="M20.05 15.296s2.53-.771 4.282-.776c2.896-.007 4.857 1.15 4.857 1.15V10.06s-1.97-1.02-4.453-1.09c-2.481-.068-4.687 1.012-4.687 1.012v5.313z" fill="#5A78FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M17.308 9.19l2.082 4.957V9.396s1.646-.882 3.485-1.13c1.84-.249 4.181.08 4.181.08l-1.133-2.707s-2.461-.159-4.69.794c-2.228.952-3.925 2.756-3.925 2.756z" fill="#5A78FF"/><path d="M21.428 5.676l-.82-1.99a1.818 1.818 0 00-2.37-.996l-5.663 2.334a1.818 1.818 0 00-.98 2.376l5.46 13.247a1.818 1.818 0 002.37.995l5.662-2.334a1.817 1.817 0 00.98-2.376l-.677-1.642s-.462-.027-.676-.033c-.226-.006-.644-.001-.644-.001l.88 2.136a.606.606 0 01-.326.793l-5.663 2.333a.606.606 0 01-.79-.331L12.711 6.94a.606.606 0 01.327-.792L18.7 3.814a.606.606 0 01.79.332l.83 2.011s.383-.187.59-.27c.206-.082.518-.211.518-.211z" fill="#5A78FF"/></svg><svg viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" width="38" height="24" role="img" aria-labelledby="pi-paypal"><title id="pi-paypal">PayPal</title><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"/><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"/><path fill="#003087" d="M23.9 8.3c.2-1 0-1.7-.6-2.3-.6-.7-1.7-1-3.1-1h-4.1c-.3 0-.5.2-.6.5L14 15.6c0 .2.1.4.3.4H17l.4-3.4 1.8-2.2 4.7-2.1z"/><path fill="#3086C8" d="M23.9 8.3l-.2.2c-.5 2.8-2.2 3.8-4.6 3.8H18c-.3 0-.5.2-.6.5l-.6 3.9-.2 1c0 .2.1.4.3.4H19c.3 0 .5-.2.5-.4v-.1l.4-2.4v-.1c0-.2.3-.4.5-.4h.3c2.1 0 3.7-.8 4.1-3.2.2-1 .1-1.8-.4-2.4-.1-.5-.3-.7-.5-.8z"/><path fill="#012169" d="M23.3 8.1c-.1-.1-.2-.1-.3-.1-.1 0-.2 0-.3-.1-.3-.1-.7-.1-1.1-.1h-3c-.1 0-.2 0-.2.1-.2.1-.3.2-.3.4l-.7 4.4v.1c0-.3.3-.5.6-.5h1.3c2.5 0 4.1-1 4.6-3.8v-.2c-.1-.1-.3-.2-.5-.2h-.1z"/></svg><svg xmlns="http://www.w3.org/2000/svg" role="img" viewBox="0 0 38 24" width="38" height="24" aria-labelledby="pi-shopify_pay"><title id="pi-shopify_pay">Shop Pay</title><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z" fill="#000"/><path d="M35.889 0C37.05 0 38 .982 38 2.182v19.636c0 1.2-.95 2.182-2.111 2.182H2.11C.95 24 0 23.018 0 21.818V2.182C0 .982.95 0 2.111 0H35.89z" fill="#5A31F4"/><path d="M9.35 11.368c-1.017-.223-1.47-.31-1.47-.705 0-.372.306-.558.92-.558.54 0 .934.238 1.225.704a.079.079 0 00.104.03l1.146-.584a.082.082 0 00.032-.114c-.475-.831-1.353-1.286-2.51-1.286-1.52 0-2.464.755-2.464 1.956 0 1.275 1.15 1.597 2.17 1.82 1.02.222 1.474.31 1.474.705 0 .396-.332.582-.993.582-.612 0-1.065-.282-1.34-.83a.08.08 0 00-.107-.035l-1.143.57a.083.083 0 00-.036.111c.454.92 1.384 1.437 2.627 1.437 1.583 0 2.539-.742 2.539-1.98s-1.155-1.598-2.173-1.82v-.003zM15.49 8.855c-.65 0-1.224.232-1.636.646a.04.04 0 01-.069-.03v-2.64a.08.08 0 00-.08-.081H12.27a.08.08 0 00-.08.082v8.194a.08.08 0 00.08.082h1.433a.08.08 0 00.081-.082v-3.594c0-.695.528-1.227 1.239-1.227.71 0 1.226.521 1.226 1.227v3.594a.08.08 0 00.081.082h1.433a.08.08 0 00.081-.082v-3.594c0-1.51-.981-2.577-2.355-2.577zM20.753 8.62c-.778 0-1.507.24-2.03.588a.082.082 0 00-.027.109l.632 1.088a.08.08 0 00.11.03 2.5 2.5 0 011.318-.366c1.25 0 2.17.891 2.17 2.068 0 1.003-.736 1.745-1.669 1.745-.76 0-1.288-.446-1.288-1.077 0-.361.152-.657.548-.866a.08.08 0 00.032-.113l-.596-1.018a.08.08 0 00-.098-.035c-.799.299-1.359 1.018-1.359 1.984 0 1.46 1.152 2.55 2.76 2.55 1.877 0 3.227-1.313 3.227-3.195 0-2.018-1.57-3.492-3.73-3.492zM28.675 8.843c-.724 0-1.373.27-1.845.746-.026.027-.069.007-.069-.029v-.572a.08.08 0 00-.08-.082h-1.397a.08.08 0 00-.08.082v8.182a.08.08 0 00.08.081h1.433a.08.08 0 00.081-.081v-2.683c0-.036.043-.054.069-.03a2.6 2.6 0 001.808.7c1.682 0 2.993-1.373 2.993-3.157s-1.313-3.157-2.993-3.157zm-.271 4.929c-.956 0-1.681-.768-1.681-1.783s.723-1.783 1.681-1.783c.958 0 1.68.755 1.68 1.783 0 1.027-.713 1.783-1.681 1.783h.001z" fill="#fff"/></svg>
                <svg viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" role="img" width="38" height="24" aria-labelledby="pi-visa"><title id="pi-visa">Visa</title><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"/><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"/><path d="M28.3 10.1H28c-.4 1-.7 1.5-1 3h1.9c-.3-1.5-.3-2.2-.6-3zm2.9 5.9h-1.7c-.1 0-.1 0-.2-.1l-.2-.9-.1-.2h-2.4c-.1 0-.2 0-.2.2l-.3.9c0 .1-.1.1-.1.1h-2.1l.2-.5L27 8.7c0-.5.3-.7.8-.7h1.5c.1 0 .2 0 .2.2l1.4 6.5c.1.4.2.7.2 1.1.1.1.1.1.1.2zm-13.4-.3l.4-1.8c.1 0 .2.1.2.1.7.3 1.4.5 2.1.4.2 0 .5-.1.7-.2.5-.2.5-.7.1-1.1-.2-.2-.5-.3-.8-.5-.4-.2-.8-.4-1.1-.7-1.2-1-.8-2.4-.1-3.1.6-.4.9-.8 1.7-.8 1.2 0 2.5 0 3.1.2h.1c-.1.6-.2 1.1-.4 1.7-.5-.2-1-.4-1.5-.4-.3 0-.6 0-.9.1-.2 0-.3.1-.4.2-.2.2-.2.5 0 .7l.5.4c.4.2.8.4 1.1.6.5.3 1 .8 1.1 1.4.2.9-.1 1.7-.9 2.3-.5.4-.7.6-1.4.6-1.4 0-2.5.1-3.4-.2-.1.2-.1.2-.2.1zm-3.5.3c.1-.7.1-.7.2-1 .5-2.2 1-4.5 1.4-6.7.1-.2.1-.3.3-.3H18c-.2 1.2-.4 2.1-.7 3.2-.3 1.5-.6 3-1 4.5 0 .2-.1.2-.3.2M5 8.2c0-.1.2-.2.3-.2h3.4c.5 0 .9.3 1 .8l.9 4.4c0 .1 0 .1.1.2 0-.1.1-.1.1-.1l2.1-5.1c-.1-.1 0-.2.1-.2h2.1c0 .1 0 .1-.1.2l-3.1 7.3c-.1.2-.1.3-.2.4-.1.1-.3 0-.5 0H9.7c-.1 0-.2 0-.2-.2L7.9 9.5c-.2-.2-.5-.5-.9-.6-.6-.3-1.7-.5-1.9-.5L5 8.2z" fill="#142688"/></svg></div></div>
    </div>
</footer>

<script>
    (function () {
        var navbar = document.querySelector('.navbar');
        function onScroll() {
            navbar.classList.toggle('scrolled', window.scrollY > 8);
        }
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }());

    function toggleNavDropdown() {
        document.getElementById('navDropdown').classList.toggle('open');
    }

    function toggleMobileMenu() {
        document.getElementById('navLinks').classList.toggle('open');
        document.getElementById('navHamburger').classList.toggle('open');
    }

    function footerNewsletterSubmit(e) {
        e.preventDefault();
        e.target.style.display = 'none';
        document.getElementById('newsletterThanks').style.display = 'block';
    }

    document.addEventListener('click', function(e) {
        var wrap = document.querySelector('.nav-dropdown-wrap');
        if (wrap && !wrap.contains(e.target)) {
            document.getElementById('navDropdown').classList.remove('open');
        }
        var navbar = document.querySelector('.navbar');
        if (navbar && !navbar.contains(e.target)) {
            document.getElementById('navLinks').classList.remove('open');
            document.getElementById('navHamburger').classList.remove('open');
        }
    });

    var searchTimer;
    var suggestUrl = <?= json_encode($this->Url->build(['controller' => 'Search', 'action' => 'suggest'])) ?>;
    var searchUrl  = <?= json_encode($this->Url->build(['controller' => 'Search', 'action' => 'search'])) ?>;
    var wishlistEnabled = <?= json_encode((bool)($identity ?? false)) ?>;
    var csrfToken = <?= json_encode($this->request->getAttribute('csrfToken')) ?>;
    var currentPagePath = <?= json_encode($this->request->getPath() . ($this->request->getUri()->getQuery() ? '?' . $this->request->getUri()->getQuery() : '')) ?>;

    function openSearch() {
        var panel = document.getElementById('searchPanel');
        panel.style.display = 'block';
        panel.setAttribute('aria-hidden', 'false');
        setTimeout(function() { document.getElementById('searchInput').focus(); }, 50);
    }

    function closeSearch() {
        var panel = document.getElementById('searchPanel');
        panel.style.display = 'none';
        panel.setAttribute('aria-hidden', 'true');
        document.getElementById('searchSuggestions').innerHTML = '';
        document.getElementById('searchInput').value = '';
        clearTimeout(searchTimer);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeSearch();
    });

    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimer);
        var q = this.value.trim();
        var box = document.getElementById('searchSuggestions');
        if (q.length < 2) { box.innerHTML = ''; return; }

        searchTimer = setTimeout(function() {
            fetch(suggestUrl + '?q=' + encodeURIComponent(q), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(function(r) { return r.json(); })
                .then(function(data) { renderSuggestions(data.results, q); });
        }, 220);
    });

    function renderSuggestions(results, q) {
        var box = document.getElementById('searchSuggestions');
        if (!results || results.length === 0) {
            box.innerHTML = '<p class="search-no-results">No results for &ldquo;' + q + '&rdquo;</p>';
            return;
        }
        var items = results.map(function(r) {
            var hasHover = r.images.length > 1 ? ' has-hover-image' : '';
            var badges = [];
            if (r.featured) {
                badges.push('<span class="product-badge product-badge--featured">Featured</span>');
            }
            if (r.is_bestsales) {
                badges.push('<span class="product-badge product-badge--bestsales">Best Seller</span>');
            }
            var badge = badges.length ? '<div class="product-card-badges">' + badges.join('') + '</div>' : '';
            var imgWrap = '<div class="search-suggest-img-wrap' + hasHover + '">'
                + badge
                + (r.images[0] ? '<img src="' + r.images[0] + '" alt="' + r.name + '" class="search-suggest-img search-suggest-img--primary">' : '<div class="search-suggest-img--empty"></div>')
                + (r.images[1] ? '<img src="' + r.images[1] + '" alt="' + r.name + '" class="search-suggest-img search-suggest-img--hover">' : '')
                + '</div>';
            var wishlistBtn = '<button class="wishlist-btn' + (wishlistEnabled && r.wishlisted ? ' wishlisted' : '') + '" data-product-id="' + r.id + '" type="button" aria-label="Save to wishlist">'
                + '<svg width="20" height="20" viewBox="0 0 64 64" fill="currentColor"><path d="M32,57C31,56.5 5,42 5,23.5C5,13.8 12.2,6.5 21,6.5C26,6.5 30.4,9 32,11.2C33.6,9 38,6.5 43,6.5C51.8,6.5 59,13.8 59,23.5C59,42 33,56.5 32,57Z"/></svg>'
                + '</button>';
            return '<div class="product-card-wrap">'
                + '<a href="' + r.url + '?back=' + encodeURIComponent(currentPagePath) + '" class="search-suggest-item">'
                + imgWrap
                + '<div class="search-suggest-info">'
                + '<span class="search-suggest-name">' + r.name + '</span>'
                + '<span class="search-suggest-price">$' + r.price + '</span>'
                + '</div>'
                + '</a>'
                + wishlistBtn
                + '</div>';
        }).join('');

        var viewAll = '<a href="' + searchUrl + '?q=' + encodeURIComponent(q) + '" class="search-view-all">'
            + 'View all results for &ldquo;' + q + '&rdquo;'
            + '</a>';

        box.innerHTML = '<div class="search-suggest-grid">' + items + '</div>' + viewAll;
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.dt-search input[type="search"]').forEach(function (input) {
            var wrap = document.createElement('div');
            wrap.className = 'dt-search-wrap';
            input.parentNode.insertBefore(wrap, input);
            wrap.appendChild(input);

            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'dt-search-clear';
            btn.setAttribute('aria-label', 'Clear search');
            btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
            wrap.appendChild(btn);

            function update() {
                wrap.classList.toggle('has-value', input.value.length > 0);
            }

            input.addEventListener('input', update);
            btn.addEventListener('click', function () {
                input.value = '';
                input.dispatchEvent(new Event('input', { bubbles: true }));
                update();
                input.focus();
            });

            update();
        });
    });

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.wishlist-btn');
        if (!btn) return;
        e.preventDefault();
        e.stopPropagation();

        var id = btn.dataset.productId;
        var wasWishlisted = btn.classList.contains('wishlisted');
        btn.disabled = true;
        fetch('<?= $this->Url->build('/profile/wishlist/toggle/') ?>' + id, {
            method: 'POST',
            headers: { 'X-CSRF-Token': csrfToken }
        })
            .then(function (r) {
                if (!r.ok) throw new Error('Wishlist request failed');
                return r.json();
            })
            .then(function (data) {
                btn.classList.toggle('wishlisted', data.wishlisted);
                if (data.guest) showWishlistToast();
                updateWishlistBadge(data.wishlisted ? 1 : -1);
            })
            .catch(function (err) {
                btn.classList.toggle('wishlisted', wasWishlisted);
                console.error('Wishlist error:', err);
                window.alert('Could not update your wishlist. Please try again.');
            })
            .finally(function () { btn.disabled = false; });
    });

    function updateWishlistBadge(delta) {
        var wrap = document.querySelector('a[title="Wishlist"].nav-cart-wrap');
        if (!wrap) return;
        var badge = wrap.querySelector('.nav-cart-badge');
        var current = badge ? parseInt(badge.textContent, 10) : 0;
        var next = Math.max(0, current + delta);
        if (next > 0) {
            if (badge) {
                badge.textContent = next;
            } else {
                badge = document.createElement('span');
                badge.className = 'nav-cart-badge';
                badge.textContent = next;
                wrap.appendChild(badge);
            }
        } else if (badge) {
            badge.remove();
        }
    }

    function showWishlistToast() {
        var existing = document.getElementById('wishlist-guest-toast');
        if (existing) { existing.remove(); }
        var toast = document.createElement('div');
        toast.id = 'wishlist-guest-toast';
        toast.innerHTML = 'To save your wishlist please <a href="<?= $this->Url->build(['controller' => 'Auth', 'action' => 'login']) ?>">login</a> or <a href="<?= $this->Url->build(['controller' => 'Auth', 'action' => 'register']) ?>">sign up</a>.';
        document.body.appendChild(toast);
        setTimeout(function () { toast.classList.add('show'); }, 10);
        setTimeout(function () {
            toast.classList.remove('show');
            setTimeout(function () { toast.remove(); }, 300);
        }, 3500);
    }
</script>
</body>
</html>
