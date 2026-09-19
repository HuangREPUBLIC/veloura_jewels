<?php
/**
 * @var \App\View\AppView $this
 */
$this->extend('/Common/care');
$this->assign('title', 'Privacy Policy');
$this->assign('intro', 'We collect only what we need to make, send and care for your order, and we never sell your information.');
$this->assign('updated', '1 September 2026');
?>
<section id="who-we-are">
    <h2>Who we are</h2>
    <p>Veloura Jewels is a jewellery and home decor studio based in Melbourne, Australia. We handle personal information in line with the Privacy Act 1988 (Cth) and the Australian Privacy Principles.</p>
</section>

<section id="what-we-collect">
    <h2>Information we collect</h2>
    <ul>
        <li><strong>Account details</strong>, such as your name, email address and password, which is stored encrypted</li>
        <li><strong>Order details</strong>, including delivery and billing addresses, phone number and purchase history</li>
        <li><strong>Messages</strong> you send us through our contact form</li>
        <li><strong>Browsing data</strong>, such as your cart, wishlist and basic device information</li>
    </ul>
    <p>Card payments are processed securely by Stripe. We never see or store your full card number.</p>
</section>

<section id="how-we-use-it">
    <h2>How we use it</h2>
    <ul>
        <li>To process, deliver and support your orders</li>
        <li>To manage your account, wishlist and order history</li>
        <li>To reply to your enquiries and return requests</li>
        <li>To send news and offers, only if you have subscribed</li>
        <li>To keep our website secure and prevent fraud</li>
    </ul>
</section>

<section id="sharing">
    <h2>Who we share it with</h2>
    <p>We share information only with the partners who help us run the store: Stripe for payments, our couriers for delivery, and our email provider for order updates. Each partner may use it only to provide their service to us. We never sell or rent your personal information.</p>
</section>

<section id="cookies">
    <h2>Cookies</h2>
    <p>We use essential cookies to keep you signed in, remember your cart and protect our forms from spam. We do not use advertising or cross-site tracking cookies.</p>
</section>

<section id="security">
    <h2>Storage and security</h2>
    <p>Your information is stored on secure servers and sent over encrypted connections. We keep order records for 7 years to meet Australian tax requirements, and delete other information once it is no longer needed.</p>
</section>

<section id="your-rights">
    <h2>Your choices and rights</h2>
    <p>You can view and update your details at any time in <?= $this->Html->link('your profile', ['controller' => 'Profile', 'action' => 'index']) ?>, unsubscribe from emails using the link in any message, or ask us for a copy of your information or to delete it.</p>
    <p>If you are unhappy with how we have handled your information, please tell us first. You can also contact the Office of the Australian Information Commissioner at oaic.gov.au.</p>
</section>

<section id="contact">
    <h2>Contact us</h2>
    <p>For any privacy question or request, <?= $this->Html->link('send us a message', ['controller' => 'ContactSubmissions', 'action' => 'add']) ?> and we will respond within 30 days.</p>
</section>
