<?php
/**
 * Quick view shell — mounted once per page from templates/layout/default.php.
 * Filled by webroot/js/cart-drawer.js with the markup quickAdd() returns for
 * products that still need a size.
 *
 * @var \App\View\AppView $this
 */
?>
<div class="quick-view-root" id="quickViewRoot" aria-hidden="true">
    <div class="quick-view-backdrop" id="quickViewBackdrop"></div>

    <div class="quick-view-panel" role="dialog" aria-modal="true" aria-label="Product quick view">
        <button type="button" class="quick-view-close" id="quickViewClose" aria-label="Close quick view">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>

        <div class="quick-view-body" id="quickViewBody"></div>
    </div>
</div>
