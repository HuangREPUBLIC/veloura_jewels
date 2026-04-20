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
<html lang="">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon', '/img/icon.png') ?>


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <?= $this->Html->css(['normalize.min', 'fonts', 'default-styles', 'cake', 'login', 'live-chat']) ?>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
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
            $this->Html->image('logo.png', ['alt' => 'Veloura Jewels', 'class' => 'navbar-logo']),
            '/',
            ['escape' => false]
        ) ?>

        <button class="nav-hamburger" id="navHamburger" onclick="toggleMobileMenu()" title="Menu" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>


    <nav class="navbar-links" id="navLinks">
        <?php if (in_array($role, ['admin', 'staff'])): ?>
        <?= $this->Html->link('Home', '/') ?>
        <?= $this->Html->link('Jewelry', '/jewelry') ?>
        <?= $this->Html->link('HomeDecor', '/home-decor') ?>
        <?php else : ?>
            <?= $this->Html->link('Home', '/') ?>
            <?= $this->Html->link('Jewelry', '/jewelry') ?>
            <?= $this->Html->link('HomeDecor', '/home-decor') ?>
            <?= $this->Html->link('Contact', '/contact') ?>
        <?php endif ?>
    </nav>


    <div class="navbar-right">
        <!-- Search icon (no function yet) -->
        <button class="nav-icon-btn" title="Search">
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
                    <span class="nav-dropdown-label">My Account</span>
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
                    <span class="nav-dropdown-label">Admin</span>
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

<main class="main-content">
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
</main>

<footer class="footer">
    <div class="footer-brand">
        <h3>Veloura Jewels</h3>
        <p>Opening hours: 10:00AM - 6:00PM</p>
        <p>123 456 7890</p>
        <p>veloura.jewels@gmail.com</p>
        <p>88 Elizabeth Road, Melbourne, VIC 3000</p>
    </div>
    <div class="footer-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms &amp; Conditions</a>
        <a href="#">Refund Policy</a>
        <a href="#">Shipping Policy</a>
    </div>
</footer>

<script>
    function toggleNavDropdown() {
        document.getElementById('navDropdown').classList.toggle('open');
    }

    function toggleMobileMenu() {
        document.getElementById('navLinks').classList.toggle('open');
        document.getElementById('navHamburger').classList.toggle('open');
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
</script>
</body>
</html>
