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
        <?= $this->Html->link('Home', '/') ?>
        <?= $this->Html->link('Contact', '/contact') ?>
    </nav>
    <div class="navbar-right">
        <span class="cart">Cart (0)</span>
        <?= $this->Html->link('Login', '/auth/login', ['class' => 'btn-login']) ?>

    </div>
</header>

<main class="main-content">
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
</main>

<footer class="footer">
    <div class="footer-brand">
        <h3>Veloura Jewels</h3>
        <p>Opening hours: 10:00 - 6:00</p>
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
