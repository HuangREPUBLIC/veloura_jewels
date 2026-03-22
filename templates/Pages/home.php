<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.10.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$this->assign('title', 'Home');
$this->Html->css('home', ['block' => true]);
?>

<div class="welcome-wrapper">
<!-- Welcome Section -->
<section class="welcome">
    <div class="welcome-text">
        <div>
            <h1>Welcome<br>to Veloura<br>Jewels</h1>
            <p class="subtitle">Discover Unique Creations</p>
            <p>Immerse yourself in the artistry of our handcrafted jewelry and home décor. Each piece is lovingly designed to tell a story and enhance your everyday life with elegance.</p>
        </div>
        <a href="#" class="btn-outline">Explore Our Collections</a>
    </div>
    <div class="welcome-image">
        <span class="placeholder-text">Jewellery Image</span>
    </div>
</section>
</div>

<!-- About Section -->
<section class="about">
    <div class="about-left">
        <h2>About<br>Veloura Jewels</h2>
        <p class="journey-label">Our Journey</p>
    </div>
    <div class="about-right">
        <p>Founded by Sarah Smith in Brooksdale, Veloura Jewels is dedicated to creating unique, handcrafted pieces that blend creativity and meaningful design. Our goal is to bring beauty and elegance into your home and wardrobe.</p>
        <?= $this->Html->link('Learn More About Us', '#', ['class' => 'btn-outline']) ?>
    </div>
</section>

<!-- Get in Touch Section -->
<section class="get-in-touch">
    <h2>Get in Touch</h2>
    <p>Have questions or need assistance? We're here to help! Reach out to us and let us know how we can assist you with our handcrafted jewelry and décor.</p>
    <?= $this->Html->link('Contact Us', '/contact', ['class' => 'btn-outline']) ?>
</section>

