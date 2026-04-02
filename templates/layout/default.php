<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'Veloura Jewels';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon', '/img/icon.png') ?>
    <?= $this->Html->css(['normalize.min', 'fonts', 'default-styles', 'cake']) ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
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
