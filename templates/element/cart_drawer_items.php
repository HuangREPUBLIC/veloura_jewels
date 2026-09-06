<?php
/**
 * Cart drawer contents — re-rendered on its own whenever the cart changes.
 *
 * @var \App\View\AppView $this
 * @var array<\App\Model\Entity\Product> $products
 * @var float|int $total
 */
?>
<?php if (empty($products)): ?>
    <div class="cart-drawer-empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="4" y="7" width="16" height="13" rx="1.5"/><path d="M8.5 7V5a3.5 3.5 0 0 1 7 0V7"/>
        </svg>
        <p class="cart-drawer-empty-title">Your cart is empty</p>
        <p class="cart-drawer-empty-text">Discover our handcrafted pieces.</p>
    </div>
<?php else: ?>
    <div class="cart-drawer-list">
        <?php foreach ($products as $product): ?>
            <article class="cart-drawer-item" data-cart-key="<?= h($product->cart_key) ?>">
                <a class="cart-drawer-thumb" href="<?= $this->Url->build('/jewellery/view/' . $product->id) ?>">
                    <?php if (!empty($product->product_images)): ?>
                        <img src="<?= $this->Url->image('products/' . h($product->product_images[0]->filename)) ?>"
                             alt="<?= h($product->name) ?>">
                    <?php else: ?>
                        <span class="cart-drawer-thumb-empty"></span>
                    <?php endif; ?>
                </a>

                <div class="cart-drawer-info">
                    <a class="cart-drawer-name" href="<?= $this->Url->build('/jewellery/view/' . $product->id) ?>">
                        <?= h($product->name) ?>
                    </a>
                    <p class="cart-drawer-price">$<?= number_format((float)$product->sale_price, 2) ?></p>
                    <p class="cart-drawer-size"><?= h($product->variant->size) ?></p>

                    <div class="cart-drawer-actions">
                        <div class="cart-drawer-qty" data-max="<?= (int)$product->variant->stock ?>">
                            <button type="button" class="cart-drawer-qty-btn" data-dir="minus" aria-label="Decrease quantity">−</button>
                            <span class="cart-drawer-qty-value"><?= (int)$product->quantity ?></span>
                            <button type="button" class="cart-drawer-qty-btn" data-dir="plus" aria-label="Increase quantity">+</button>
                        </div>
                        <button type="button" class="cart-drawer-remove">Remove</button>
                    </div>
                    <span class="cart-drawer-note" role="alert" aria-live="polite"></span>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
