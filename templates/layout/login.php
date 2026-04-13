<?php
$appLocale = \Cake\Core\Configure::read('App.defaultLocale');
?>
<!DOCTYPE html>
<html lang="<?= $appLocale ?>">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->fetch('title') ?> - Veloura Jewels</title>
    <?= $this->Html->meta('icon', '/img/icon.png') ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">

    <?= $this->Html->css(['normalize.min', 'fonts', 'default-styles', 'cake', 'login']) ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>

<header class="navbar">
    <div class="navbar-brand">
        <?= $this->Html->link(
            $this->Html->image('logo.png', ['alt' => 'Veloura Jewels', 'class' => 'navbar-logo']),
            '/',
            ['escape' => false]
        ) ?>
    </div>

    <nav class="navbar-links">
        <?php
        $identity = $this->request->getAttribute('identity');
        $role = $identity ? $identity->get('role') : null;
        ?>
        <?= $this->Html->link('Home', '/') ?>
        <?= $this->Html->link('Jewelry', '/jewelry') ?>
        <?php if ($role === 'customer' || !$role): ?>
            <?= $this->Html->link('Contact', '/contact') ?>
        <?php else: ?>
            <?= $this->Html->link('Admin', ['controller' => 'Users', 'action' => 'dashboard']) ?>
        <?php endif; ?>
    </nav>

    <div class="navbar-right">
        <?php
        $cart = $this->request->getSession()->read('Cart') ?? [];
        $count = count($cart);
        ?>
        <?= $this->Html->link("Cart ($count)", ['controller' => 'Jewelry', 'action' => 'cart'], ['class' => 'cart']) ?>

        <?php if ($this->Identity->isLoggedIn()): ?>
            <?php if ($role === 'customer'): ?>
                <?= $this->Html->link(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg> Profile',
                    ['controller' => 'Profile', 'action' => 'index'],
                    ['class' => 'btn-profile', 'escape' => false]
                ) ?>
            <?php endif; ?>
            <?= $this->Html->link('Logout', '/auth/logout', ['class' => 'btn-login']) ?>
        <?php else: ?>
            <?= $this->Html->link('Login', '/auth/login', ['class' => 'btn-login']) ?>
        <?php endif; ?>
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

</body>
</html>
