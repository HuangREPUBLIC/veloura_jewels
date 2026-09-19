<?php
/**
 * @var \App\View\AppView $this
 */
$this->extend('/Common/care');
$this->assign('title', 'Refund Policy');
$this->assign('intro', 'If a piece is not quite right, you have 30 days to return or exchange it. Here is how it works.');
$this->assign('updated', '1 September 2026');
?>
<ul class="care-facts">
    <li><strong>30 days</strong><span>To return or exchange</span></li>
    <li><strong>Free</strong><span>Prepaid returns within Australia</span></li>
    <li><strong>5-10 days</strong><span>For your refund to arrive</span></li>
</ul>

<section id="eligibility">
    <h2>What can be returned</h2>
    <p>You can return any item within 30 days of delivery for a refund or exchange, as long as it is:</p>
    <ul>
        <li>Unworn, unaltered and in the condition you received it</li>
        <li>In its original gift box with the care card and any tags attached</li>
        <li>Accompanied by your order number</li>
    </ul>
</section>

<section id="exclusions">
    <h2>Items we cannot accept</h2>
    <ul>
        <li>Pierced earrings, for hygiene reasons, unless faulty</li>
        <li>Personalised, engraved or made-to-measure pieces</li>
        <li>Items that show signs of wear, resizing or damage after delivery</li>
    </ul>
</section>

<section id="how-to-return">
    <h2>How to start a return</h2>
    <ol>
        <li>Send us a message through our <?= $this->Html->link('contact form', ['controller' => 'ContactSubmissions', 'action' => 'add']) ?> with your order number and the items you are returning.</li>
        <li>We will reply within one business day with a prepaid label for Australian orders, or a return address for international orders.</li>
        <li>Pack the items securely in their original box and drop the parcel off within 7 days.</li>
    </ol>
</section>

<section id="refunds">
    <h2>Refunds</h2>
    <p>Once your return reaches our studio we inspect it within 3 business days and email you to confirm. Refunds go back to your original payment method, including Apple Pay and Klarna, and usually appear within 5 to 10 business days depending on your bank.</p>
    <p>International return postage and any duties paid on delivery are not refundable.</p>
</section>

<section id="exchanges">
    <h2>Exchanges</h2>
    <p>Need a different size or finish? We offer one free exchange per order, subject to availability. Mention the item you would like in your return message and we will ship it as soon as your return arrives.</p>
</section>

<section id="faulty">
    <h2>Faulty or damaged items</h2>
    <p>Our pieces come with guarantees that cannot be excluded under the Australian Consumer Law. If an item is faulty, arrives damaged or is not as described, contact us with photos and we will repair, replace or refund it at no cost to you, even after 30 days.</p>
</section>
