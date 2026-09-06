/**
 * Slide-out cart. Replaces the old cart page: adding, changing quantities
 * and removing all happen over fetch, and the drawer re-renders itself from
 * the HTML fragment the server returns (element/cart_drawer_items.php).
 * Pages that finish a server-side cart change come back with `?cart=open`
 * so the drawer shows the result.
 */
(function () {
    'use strict';

    var body = document.body;

    var config = {
        drawerUrl:   body.dataset.cartDrawerUrl,
        updateUrl:   body.dataset.cartUpdateUrl,
        removeUrl:   body.dataset.cartRemoveUrl,
        quickAddUrl: body.dataset.cartQuickAddUrl,
        csrfToken:   body.dataset.csrfToken
    };

    var root      = document.getElementById('cartDrawerRoot');
    var panelBody = document.getElementById('cartDrawerBody');
    var foot      = document.getElementById('cartDrawerFoot');
    var totalEl   = document.getElementById('cartDrawerTotal');

    var quickView     = document.getElementById('quickViewRoot');
    var quickViewBody = document.getElementById('quickViewBody');

    if (!root || !panelBody || !foot) return;

    var loaded = false;
    var lastFocused = null;

    function open() {
        lastFocused = document.activeElement;
        root.classList.add('is-open');
        root.setAttribute('aria-hidden', 'false');
        body.classList.add('cart-drawer-open');

        if (!loaded) load();

        var closeBtn = document.getElementById('cartDrawerClose');
        if (closeBtn) closeBtn.focus();
    }

    function close() {
        root.classList.remove('is-open');
        root.setAttribute('aria-hidden', 'true');
        body.classList.remove('cart-drawer-open');

        if (lastFocused && typeof lastFocused.focus === 'function') {
            lastFocused.focus();
        }
    }

    function openQuickView(html) {
        if (!quickView || !quickViewBody) return;

        quickViewBody.innerHTML = html;
        quickView.classList.add('is-open');
        quickView.setAttribute('aria-hidden', 'false');
        body.classList.add('cart-drawer-open');
        initQuickViewGallery();
    }

    // Same slide-and-dots switching the detail page uses for its images.
    function initQuickViewGallery() {
        var stack = document.getElementById('quickViewStack');
        var dots  = document.querySelectorAll('.quick-view-dot');
        if (!stack || !dots.length) return;

        stack.addEventListener('scroll', function () {
            var index = Math.round(stack.scrollLeft / stack.offsetWidth);
            dots.forEach(function (dot, i) { dot.classList.toggle('active', i === index); });
        }, { passive: true });

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                stack.scrollTo({ left: parseInt(this.dataset.index, 10) * stack.offsetWidth, behavior: 'smooth' });
            });
        });
    }

    function closeQuickView() {
        if (!quickView) return;

        quickView.classList.remove('is-open');
        quickView.setAttribute('aria-hidden', 'true');

        if (!root.classList.contains('is-open')) {
            body.classList.remove('cart-drawer-open');
        }
    }

    function request(url, options) {
        var opts = options || {};
        var headers = { 'X-Requested-With': 'XMLHttpRequest' };

        if (opts.method === 'POST') {
            headers['X-CSRF-Token'] = config.csrfToken;
        }

        return fetch(url, {
            method: opts.method || 'GET',
            headers: headers,
            body: opts.body,
            credentials: 'same-origin'
        }).then(function (res) {
            if (!res.ok) throw new Error('Cart request failed: ' + res.status);
            return res.json();
        });
    }

    function render(data) {
        loaded = true;
        panelBody.innerHTML = data.html;
        foot.hidden = data.count === 0;
        if (totalEl) totalEl.textContent = data.total;
        updateBadge(data.count);
    }

    function load() {
        return request(config.drawerUrl)
            .then(render)
            .catch(function (err) {
                console.error(err);
                panelBody.innerHTML = '<div class="cart-drawer-empty">'
                    + '<p class="cart-drawer-empty-title">Something went wrong</p>'
                    + '<p class="cart-drawer-empty-text">Please refresh and try again.</p>'
                    + '</div>';
                foot.hidden = true;
            });
    }

    function post(url, params) {
        var form = new URLSearchParams();
        Object.keys(params).forEach(function (k) { form.append(k, params[k]); });

        return request(url, { method: 'POST', body: form });
    }

    function updateBadge(count) {
        var wrap = document.getElementById('cartDrawerToggle');
        if (!wrap) return;

        var badge = wrap.querySelector('.nav-cart-badge');

        if (count > 0) {
            if (badge) {
                badge.textContent = count;
            } else {
                badge = document.createElement('span');
                badge.className = 'nav-cart-badge';
                badge.textContent = count;
                wrap.appendChild(badge);
            }
        } else if (badge) {
            badge.remove();
        }
    }

    function noteFor(item, message) {
        var note = item.querySelector('.cart-drawer-note');
        if (!note) return;

        note.textContent = message;
        note.classList.add('is-shown');
        setTimeout(function () { note.classList.remove('is-shown'); }, 2600);
    }

    // Header bag icon — intercept the link to /cart.
    var toggle = document.getElementById('cartDrawerToggle');
    if (toggle) {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            open();
        });
    }

    document.addEventListener('click', function (e) {
        if (e.target.closest('#cartDrawerClose') || e.target.closest('#cartDrawerBackdrop')) {
            close();
        }
        if (e.target.closest('#quickViewClose') || e.target.closest('#quickViewBackdrop')) {
            closeQuickView();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;

        if (quickView && quickView.classList.contains('is-open')) {
            closeQuickView();
        } else if (root.classList.contains('is-open')) {
            close();
        }
    });

    // Quick view's own add to cart — same endpoint as the detail page.
    document.addEventListener('submit', function (e) {
        var form = e.target.closest('.quick-view-form');
        if (!form) return;

        e.preventDefault();

        var submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        request(form.action, { method: 'POST', body: new FormData(form) })
            .then(function (data) {
                closeQuickView();
                render(data);
                open();
            })
            .catch(function (err) {
                console.error(err);
                form.submit();
            })
            .finally(function () {
                if (submitBtn) submitBtn.disabled = false;
            });
    });

    // Quantity steppers and remove buttons inside the drawer.
    panelBody.addEventListener('click', function (e) {
        var item = e.target.closest('.cart-drawer-item');
        if (!item) return;

        var key = item.dataset.cartKey;

        var qtyBtn = e.target.closest('.cart-drawer-qty-btn');
        if (qtyBtn) {
            var wrap    = qtyBtn.closest('.cart-drawer-qty');
            var valueEl = wrap.querySelector('.cart-drawer-qty-value');
            var current = parseInt(valueEl.textContent, 10) || 1;
            var max     = parseInt(wrap.dataset.max, 10) || 99;
            var next    = qtyBtn.dataset.dir === 'plus' ? current + 1 : current - 1;

            if (next > max) {
                noteFor(item, 'Only ' + max + ' in stock');
                return;
            }

            // Stepping below one removes the line, which is what the server
            // already does for a quantity of zero.
            if (next < 1) next = 0;

            item.classList.add('is-busy');
            post(config.updateUrl, { cart_key: key, quantity: next })
                .then(render)
                .catch(function (err) {
                    console.error(err);
                    item.classList.remove('is-busy');
                });
            return;
        }

        if (e.target.closest('.cart-drawer-remove')) {
            item.classList.add('is-busy', 'is-leaving');
            post(config.removeUrl, { cart_key: key })
                .then(render)
                .catch(function (err) {
                    console.error(err);
                    item.classList.remove('is-busy', 'is-leaving');
                });
        }
    });

    // Product detail page — add to cart without leaving the page.
    var addForm = document.querySelector('.add-to-cart-form');
    if (addForm) {
        addForm.addEventListener('submit', function (e) {
            e.preventDefault();

            var submitBtn = addForm.querySelector('button[type="submit"], input[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            request(addForm.action, { method: 'POST', body: new FormData(addForm) })
                .then(function (data) {
                    render(data);
                    open();
                })
                .catch(function (err) {
                    console.error(err);
                    addForm.submit();
                })
                .finally(function () {
                    if (submitBtn) submitBtn.disabled = false;
                });
        });
    }

    // Listing cards — quick add. Products with more than one size in stock are
    // sent to their detail page instead, so the size is always chosen.
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.quick-add-btn');
        if (!btn) return;

        e.preventDefault();
        e.stopPropagation();

        if (btn.disabled) return;
        btn.disabled = true;

        post(config.quickAddUrl, { product_id: btn.dataset.productId })
            .then(function (data) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }
                if (data.quickView) {
                    openQuickView(data.quickView);
                    return;
                }
                render(data);
                open();
            })
            .catch(function (err) {
                console.error(err);
                if (btn.dataset.productUrl) window.location.href = btn.dataset.productUrl;
            })
            .finally(function () { btn.disabled = false; });
    });

    if (new URLSearchParams(window.location.search).get('cart') === 'open') {
        open();
    }
})();
