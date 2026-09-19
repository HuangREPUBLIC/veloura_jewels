<?php
/**
 * Slide-out cart drawer, mounted once per page by the default layout.
 * Contents are loaded lazily by webroot/js/cart-drawer.js on first open.
 *
 * @var \App\View\AppView $this
 */
?>
<div class="cart-drawer-root" id="cartDrawerRoot" aria-hidden="true">
    <div class="cart-drawer-backdrop" id="cartDrawerBackdrop"></div>

    <aside class="cart-drawer" role="dialog" aria-modal="true" aria-label="Shopping cart">
        <header class="cart-drawer-head">
            <h2 class="cart-drawer-title">Cart</h2>
            <button type="button" class="cart-drawer-close" id="cartDrawerClose" aria-label="Close cart">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </header>

        <div class="cart-drawer-body" id="cartDrawerBody">
            <div class="cart-drawer-loading" aria-hidden="true">
                <span></span><span></span><span></span>
            </div>
        </div>

        <footer class="cart-drawer-foot" id="cartDrawerFoot" hidden>
            <ul class="cart-drawer-assurances">
                <li>
                    <a href="<?= $this->Url->build('/shipping-policy') ?>">
                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1.5 5.5h13v11h-13zM14.5 9h4.5l3.5 3.8v3.7h-8"/><circle cx="6" cy="17.5" r="2"/><circle cx="18" cy="17.5" r="2"/>
                        </svg>
                        Free shipping
                    </a>
                </li>
                <li>
                    <a href="<?= $this->Url->build('/shipping-policy#packaging') ?>">
                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="8" width="18" height="13" rx="1"/><path d="M12 8v13M3 12h18"/>
                            <path d="M12 8S9.5 3.5 7.5 4.6 9 8 12 8zM12 8s2.5-4.5 4.5-3.4S15 8 12 8z"/>
                        </svg>
                        Gift packaging
                    </a>
                </li>
                <li>
                    <a href="<?= $this->Url->build('/refund-policy') ?>">
                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 1 1 3 6.7"/><polyline points="3 6 3 12 9 12"/>
                        </svg>
                        30-day returns
                    </a>
                </li>
            </ul>

            <?= $this->Form->create(null, [
                'url'        => ['controller' => 'Jewelry', 'action' => 'createCheckoutSession'],
                'class'      => 'cart-drawer-checkout-form',
                'novalidate' => true,
            ]) ?>
            <button type="submit" class="cart-drawer-checkout">
                <span>Checkout</span>
                <span class="cart-drawer-checkout-dot">·</span>
                <span class="cart-drawer-total" id="cartDrawerTotal">$0.00</span>
            </button>
            <?= $this->Form->end() ?>
        </footer>
    </aside>
</div>
