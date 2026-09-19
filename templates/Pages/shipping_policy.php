<?php
/**
 * @var \App\View\AppView $this
 */
$this->extend('/Common/care');
$this->assign('title', 'Shipping Policy');
$this->assign('intro', 'Every piece is wrapped by hand in our Melbourne studio and sent fully tracked, with shipping included on every order.');
$this->assign('updated', '1 September 2026');
?>
<ul class="care-facts">
    <li><strong>Free</strong><span>Tracked shipping on every order</span></li>
    <li><strong>1-2 days</strong><span>To pack and dispatch your order</span></li>
    <li><strong>8 countries</strong><span>Delivered to your door</span></li>
</ul>

<section id="processing">
    <h2>Order processing</h2>
    <p>Orders placed before 1pm AEST on a business day are packed and dispatched within 1 to 2 business days. Orders placed on weekends or public holidays are processed the next business day.</p>
    <p>Made-to-order and personalised pieces take an extra 5 to 10 business days to craft. We will email you when your piece leaves the studio.</p>
</section>

<section id="rates">
    <h2>Delivery times and costs</h2>
    <table>
        <thead>
            <tr><th scope="col">Destination</th><th scope="col">Delivery</th><th scope="col">Cost</th></tr>
        </thead>
        <tbody>
            <tr><td>Melbourne metro</td><td>1-2 days</td><td>Free</td></tr>
            <tr><td>Rest of Australia</td><td>2-6 days</td><td>Free</td></tr>
            <tr><td>New Zealand</td><td>5-8 days</td><td>Free</td></tr>
            <tr><td>US, UK, Canada, Singapore, Hong Kong, Japan</td><td>7-14 days</td><td>Free</td></tr>
            <tr><td>Collect from our Melbourne store</td><td>Next day</td><td>Free</td></tr>
        </tbody>
    </table>
    <p>Delivery times are business days, estimated from the day of dispatch, and may be longer during sale periods or peak holidays.</p>
</section>

<section id="tracking">
    <h2>Tracking your order</h2>
    <p>Once your order ships you will receive an email with a tracking link. You can also follow every order from <?= $this->Html->link('My Orders', ['controller' => 'Profile', 'action' => 'orders']) ?> in your account.</p>
</section>

<section id="packaging">
    <h2>Packaging and signature</h2>
    <p>Each piece arrives in our signature gift box with a care card, ready to give. Orders over $500 AUD require a signature on delivery and are fully insured until they reach you.</p>
</section>

<section id="international">
    <h2>International orders</h2>
    <p>Prices are shown in Australian dollars. Import duties and local taxes are set by the destination country and are payable by the recipient on arrival.</p>
</section>

<section id="issues">
    <h2>Delayed, lost or damaged parcels</h2>
    <p>If your parcel has not arrived within the times above, <?= $this->Html->link('contact us', ['controller' => 'ContactSubmissions', 'action' => 'add']) ?> with your order number and we will trace it with the courier. If it arrives damaged, let us know within 48 hours of delivery with a few photos.</p>
    <p>Either way we will send a replacement or refund you in full. See our <?= $this->Html->link('Refund Policy', '/refund-policy') ?> for details.</p>
</section>
