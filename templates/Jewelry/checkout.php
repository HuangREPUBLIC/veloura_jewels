<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $products
 * @var float|int $total
 */

$this->assign('title', 'Checkout');
$this->Html->css('jewelry', ['block' => true]);
?>

<div class="jewelry-page">
    <section class="jewelry-hero">
        <h1>Checkout</h1>
        <p>Review your order and continue to Stripe payment.</p>
    </section>

    <div class="cart-layout">
        <div class="cart-items">
            <?php foreach ($products as $product): ?>
                <div class="cart-item">
                    <div class="cart-item-image">
                        <?php if (!empty($product->product_images)): ?>
                            <img
                                src="<?= $this->Url->image(h($product->product_images[0]->filename)) ?>"
                                alt="<?= h($product->name) ?>"
                                class="cart-image"
                            >
                        <?php else: ?>
                            <div class="cart-placeholder"><span>No Image</span></div>
                        <?php endif; ?>
                    </div>

                    <div class="cart-item-info">
                        <h3><?= h($product->name) ?></h3>
                        <p>Price: $<?= number_format((float)$product->sale_price, 2) ?></p>
                        <p>Quantity: <?= (int)$product->quantity ?></p>
                        <p>Subtotal: $<?= number_format((float)$product->subtotal, 2) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <aside class="cart-summary">
            <h2>Order Summary</h2>
            <div class="summary-row">
                <span>Total</span>
                <strong>$<?= number_format((float)$total, 2) ?></strong>
            </div>

            <?= $this->Form->create(null, [
                'url' => ['controller' => 'Jewelry', 'action' => 'createCheckoutSession']
            ]) ?>
            <?= $this->Form->button('Pay with Stripe', ['class' => 'jewelry-cart-primary-btn']) ?>
            <?= $this->Form->end() ?>

            <a href="<?= $this->Url->build('/cart') ?>" class="jewelry-cart-secondary-btn jewelry-cart-link">Back to Cart</a>
        </aside>
    </div>
</div>
