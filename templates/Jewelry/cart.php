<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $products
 * @var float|int $total
 */

$this->assign('title', 'Cart');
$this->Html->css('jewelry', ['block' => true]);
?>

<div class="jewelry-page">
    <section class="jewelry-hero">
        <h1>Your Cart</h1>
        <p>Review your selected items before checkout.</p>
    </section>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <p>Your cart is currently empty.</p>
        </div>
    <?php else: ?>
        <div class="cart-layout">
            <div class="cart-items">
                <?php foreach ($products as $product): ?>
                    <div class="cart-item">
                        <div class="cart-item-image">
                            <?php if (!empty($product->product_images)): ?>
                                <img
                                    src="<?= $this->Url->image('products/' . h($product->product_images[0]->filename)) ?>"
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
                            <p class="cart-price">Size: <?= h($product->variant->size) ?></p>
                            <p class="cart-price">Price: $<?= number_format((float)$product->sale_price, 2) ?></p>

                            <?= $this->Form->create(null, [
                                'url'        => ['controller' => 'Jewelry', 'action' => 'cart'],
                                'class'      => 'cart-update-form',
                                'novalidate' => true,
                            ]) ?>

                            <?= $this->Form->hidden('cart_key', ['value' => $product->cart_key]) ?>

                            <div class="cart-quantity-row">
                                <?= $this->Form->label('quantity_' . $product->id, 'Quantity', ['class' => 'cart-quantity-label']) ?>
                                <div class="qty-row">
                                    <div class="qty-box">
                                        <button type="button" class="jewelry-qty-minus">−</button>
                                        <input
                                            type="number"
                                            id="quantity_<?= $product->id ?>"
                                            name="quantity"
                                            value="<?= (int)$product->quantity ?>"
                                            min="1"
                                            max="<?= (int)$product->variant->stock ?>"
                                            class="cart-quantity-input"
                                        >
                                        <button type="button" class="jewelry-qty-plus">+</button>
                                    </div>
                                    <span class="qty-toast" role="alert" aria-live="assertive"></span>
                                </div>
                            </div>

                            <?= $this->Form->end() ?>

                            <p class="cart-subtotal">Subtotal: $<?= number_format((float)$product->subtotal, 2) ?></p>

                            <?= $this->Form->postLink(
                                'Remove',
                                ['controller' => 'Jewelry', 'action' => 'removeFromCart'],
                                [
                                    'data'  => ['cart_key' => $product->cart_key],
                                    'class' => 'jewelry-btn-remove',
                                ]
                            ) ?>
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
                    <a href="<?= $this->Url->build('/checkout') ?>" class="jewelry-cart-primary-btn">Proceed to Checkout</a>
                    <a href="<?= $this->Url->build('/jewelry') ?>" class="jewelry-cart-secondary-btn">Continue Shopping</a>
                </div>
            </aside>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.qty-box').forEach(function (wrapper) {
            var input = wrapper.querySelector('input');
            var minus = wrapper.querySelector('.jewelry-qty-minus');
            var plus  = wrapper.querySelector('.jewelry-qty-plus');
            var form  = wrapper.closest('form');
            var row   = wrapper.closest('.qty-row');
            var toast = row ? row.querySelector('.qty-toast') : null;
            var toastTimer;

            function showToast(msg) {
                if (!toast) return;
                clearTimeout(toastTimer);
                toast.textContent = msg;
                toast.classList.add('qty-toast--show');
                toastTimer = setTimeout(function () {
                    toast.classList.remove('qty-toast--show');
                }, 2500);
            }

            minus.addEventListener('click', function () {
                var value = parseInt(input.value || 0, 10);
                if (value > 1) {
                    input.value = value - 1;
                    form.submit();
                }
            });

            plus.addEventListener('click', function () {
                var value = parseInt(input.value || 0, 10);
                var max   = parseInt(input.max || 999999, 10);
                if (value < max) {
                    input.value = value + 1;
                    form.submit();
                } else {
                    showToast('Only ' + max + ' item' + (max === 1 ? '' : 's') + ' available');
                }
            });

            input.addEventListener('input', function () {
                var value = parseInt(this.value, 10);
                var max   = parseInt(this.max || 999999, 10);
                if (isNaN(value) || value < 1) {
                    this.value = 1;
                } else if (value > max) {
                    this.value = max;
                    showToast('Only ' + max + ' item' + (max === 1 ? '' : 's') + ' available');
                }
            });
        });
    });
</script>
