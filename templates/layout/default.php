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
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css(['normalize.min', 'fonts', 'default-styles', 'cake']) ?>

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
        <?php if ($this->request->getSession()->check('Auth.User') &&
            $this->request->getSession()->read('Auth.User.role') === 'admin'): ?>
            <?= $this->Html->link('Admin', '/dashboard') ?>
        <?php endif; ?>
    </nav>

    <div class="navbar-right">
        <span class="cart">Cart (0)</span>
        <?php if ($this->request->getSession()->check('Auth.User')): ?>
            <?= $this->Html->link('Logout', '/logout', ['class' => 'btn-login']) ?>
        <?php else: ?>
            <?= $this->Html->link('Login', '/login', ['class' => 'btn-login']) ?>
        <?php endif; ?>
    </div>
</header>

<main class="main-content">
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
</main>

</body>
</html>
