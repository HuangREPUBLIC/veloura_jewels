<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $products
 * @var float|int $total
 */

$this->assign('title', 'Cart');
echo $this->Html->css('jewelry');
?>

<div class="jewelry-page">
    <section class="jewelry-hero">
        <h1>Your Cart</h1>
        <p>Review your selected items before checkout.</p>
    </section>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <p>Your cart is currently empty.</p>
            <div style="margin-top: 1rem;">
                <a href="<?= $this->Url->build('/jewelry') ?>" class="btn-view">Continue Shopping</a>
            </div>
        </div>
    <?php else: ?>
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
                                <div class="cart-placeholder">
                                    <span>No Image</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="cart-item-info">
                            <h3><?= h($product->name) ?></h3>
                            <p class="cart-price">Unit Price: $<?= number_format((float)$product->sale_price, 2) ?></p>

                            <?= $this->Form->create(null, [
                                'url' => ['controller' => 'Jewelry', 'action' => 'cart'],
                                'class' => 'cart-update-form'
                            ]) ?>

                            <?= $this->Form->hidden('product_id', ['value' => $product->id]) ?>

                            <div class="cart-quantity-row">
                                <?= $this->Form->label('quantity_' . $product->id, 'Quantity', ['class' => 'cart-quantity-label']) ?>

                                <div class="qty-box">
                                    <button type="button" class="qty-btn minus">−</button>
                                    <input
                                        type="number"
                                        id="quantity_<?= $product->id ?>"
                                        name="quantity"
                                        value="<?= (int)$product->quantity ?>"
                                        min="1"
                                        max="<?= (int)$product->stock ?>"
                                        class="cart-quantity-input auto-submit-quantity"
                                    >
                                    <button type="button" class="qty-btn plus">+</button>
                                </div>
                            </div>

                            <?= $this->Form->end() ?>

                            <p class="cart-subtotal">Subtotal: $<?= number_format((float)$product->subtotal, 2) ?></p>

                            <a href="<?= $this->Url->build('/jewelry/remove-from-cart/' . $product->id) ?>" class="btn-remove">
                                Remove
                            </a>
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

                <div class="summary-actions">
                    <a href="<?= $this->Url->build('/jewelry') ?>" class="btn-secondary">Continue Shopping</a>
                    <a href="#" class="btn-view">Proceed to Checkout</a>
                </div>
            </aside>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.qty-box').forEach(function (wrapper) {
            const input = wrapper.querySelector('input');
            const minus = wrapper.querySelector('.minus');
            const plus = wrapper.querySelector('.plus');
            const form = wrapper.closest('form');

            minus.addEventListener('click', function () {
                let value = parseInt(input.value || 0, 10);
                if (value > 1) {
                    input.value = value - 1;
                    form.submit();
                }
            });

            plus.addEventListener('click', function () {
                let value = parseInt(input.value || 0, 10);
                const max = parseInt(input.max || 999999, 10);

                if (value < max) {
                    input.value = value + 1;
                    form.submit();
                }
            });
        });
    });
</script>
