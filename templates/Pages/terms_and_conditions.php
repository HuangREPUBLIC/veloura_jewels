<?php
/**
 * @var \App\View\AppView $this
 */
$this->extend('/Common/care');
$this->assign('title', 'Terms & Conditions');
$this->assign('intro', 'These terms apply when you browse our website or place an order with Veloura Jewels. Please read them before you buy.');
$this->assign('updated', '1 September 2026');
?>
<section id="about">
    <h2>About these terms</h2>
    <p>This website is operated by Veloura Jewels in Melbourne, Victoria. By using the site or placing an order you agree to these terms, together with our <?= $this->Html->link('Privacy Policy', '/privacy-policy') ?>, <?= $this->Html->link('Shipping Policy', '/shipping-policy') ?> and <?= $this->Html->link('Refund Policy', '/refund-policy') ?>.</p>
</section>

<section id="account">
    <h2>Your account</h2>
    <p>You are responsible for keeping your password private and for activity on your account. Please make sure your details are accurate and up to date. We may suspend accounts that are used for fraud or misuse.</p>
</section>

<section id="products">
    <h2>Products and pricing</h2>
    <p>Our pieces are handcrafted, so small variations in colour, stone and finish are part of their character. Photos are as accurate as we can make them, but screens may show colours slightly differently.</p>
    <p>All prices are in Australian dollars and include GST. If a price is listed in error, we will contact you before processing the order and you may cancel for a full refund.</p>
</section>

<section id="orders">
    <h2>Orders and payment</h2>
    <p>Your order is confirmed once payment is complete and you receive a confirmation email. Payments are processed securely by Stripe. Unpaid orders are held for 30 minutes and then released.</p>
    <p>We may decline or cancel an order if an item is unavailable or we suspect fraud. If this happens, you will be refunded in full.</p>
</section>

<section id="delivery-returns">
    <h2>Delivery and returns</h2>
    <p>Delivery times and costs are set out in our <?= $this->Html->link('Shipping Policy', '/shipping-policy') ?>. Returns, exchanges and refunds are covered by our <?= $this->Html->link('Refund Policy', '/refund-policy') ?>. Risk in an item passes to you on delivery.</p>
</section>

<section id="intellectual-property">
    <h2>Intellectual property</h2>
    <p>All designs, photography, text and logos on this site belong to Veloura Jewels. Please do not copy or reuse them without our written permission.</p>
</section>

<section id="liability">
    <h2>Your consumer rights</h2>
    <p>Nothing in these terms limits your rights under the Australian Consumer Law. To the extent allowed by law, we are not liable for indirect or unforeseeable loss arising from use of this website.</p>
</section>

<section id="changes">
    <h2>Changes and governing law</h2>
    <p>We may update these terms from time to time. The version shown here applies to orders placed after the date above. These terms are governed by the laws of Victoria, Australia.</p>
</section>
