(function () {
    var config = document.body.dataset;
    var searchTimer;

    function getById(id) {
        return document.getElementById(id);
    }

    function escapeHtml(value) {
        return String(value == null ? '' : value).replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function initNavbarScroll() {
        var navbar = document.querySelector('.navbar');
        if (!navbar) return;

        function onScroll() {
            navbar.classList.toggle('scrolled', window.scrollY > 8);
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    function toggleNavDropdown() {
        var dropdown = getById('navDropdown');
        if (dropdown) dropdown.classList.toggle('open');
    }

    function toggleMobileMenu() {
        var links = getById('navLinks');
        var hamburger = getById('navHamburger');
        if (links) links.classList.toggle('open');
        if (hamburger) hamburger.classList.toggle('open');
    }

    function closeNavMenus(e) {
        var wrap = document.querySelector('.nav-dropdown-wrap');
        var dropdown = getById('navDropdown');
        if (wrap && dropdown && !wrap.contains(e.target)) {
            dropdown.classList.remove('open');
        }

        var navbar = document.querySelector('.navbar');
        var links = getById('navLinks');
        var hamburger = getById('navHamburger');
        if (navbar && !navbar.contains(e.target)) {
            if (links) links.classList.remove('open');
            if (hamburger) hamburger.classList.remove('open');
        }
    }

    function openSearch() {
        var panel = getById('searchPanel');
        var input = getById('searchInput');
        if (!panel) return;

        panel.style.display = 'block';
        panel.setAttribute('aria-hidden', 'false');
        if (input) {
            setTimeout(function () { input.focus(); }, 50);
        }
    }

    function closeSearch() {
        var panel = getById('searchPanel');
        var suggestions = getById('searchSuggestions');
        var input = getById('searchInput');
        if (!panel) return;

        panel.style.display = 'none';
        panel.setAttribute('aria-hidden', 'true');
        if (suggestions) suggestions.innerHTML = '';
        if (input) input.value = '';
        clearTimeout(searchTimer);
    }

    function initSearch() {
        var input = getById('searchInput');
        var openBtn = getById('searchOpenBtn');
        var closeBtn = getById('searchCloseBtn');
        var backdrop = getById('searchBackdrop');

        if (openBtn) openBtn.addEventListener('click', openSearch);
        if (closeBtn) closeBtn.addEventListener('click', closeSearch);
        if (backdrop) backdrop.addEventListener('click', closeSearch);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeSearch();
        });

        if (!input) return;

        input.addEventListener('input', function () {
            clearTimeout(searchTimer);

            var q = input.value.trim();
            var box = getById('searchSuggestions');
            if (!box) return;
            if (q.length < 2) {
                box.innerHTML = '';
                return;
            }

            searchTimer = setTimeout(function () {
                fetch(config.searchSuggestUrl + '?q=' + encodeURIComponent(q), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(function (r) { return r.json(); })
                    .then(function (data) { renderSuggestions(data.results, q); });
            }, 220);
        });
    }

    function renderSuggestions(results, q) {
        var box = getById('searchSuggestions');
        if (!box) return;

        if (!results || results.length === 0) {
            box.innerHTML = '<p class="search-no-results">No results for &ldquo;' + escapeHtml(q) + '&rdquo;</p>';
            return;
        }

        var wishlistEnabled = config.wishlistEnabled === '1';
        var items = results.map(function (r) {
            var name = escapeHtml(r.name);
            var url = escapeHtml(r.url);
            var price = escapeHtml(r.price);
            var hasHover = r.images.length > 1 ? ' has-hover-image' : '';
            var badges = [];

            if (r.featured) {
                badges.push('<span class="product-badge product-badge--featured">Featured</span>');
            }
            if (r.is_bestsales) {
                badges.push('<span class="product-badge product-badge--bestsales">Best Seller</span>');
            }

            var badge = badges.length ? '<div class="product-card-badges">' + badges.join('') + '</div>' : '';
            var imgWrap = '<div class="search-suggest-img-wrap' + hasHover + '">'
                + badge
                + (r.images[0] ? '<img src="' + escapeHtml(r.images[0]) + '" alt="' + name + '" class="search-suggest-img search-suggest-img--primary">' : '<div class="search-suggest-img--empty"></div>')
                + (r.images[1] ? '<img src="' + escapeHtml(r.images[1]) + '" alt="' + name + '" class="search-suggest-img search-suggest-img--hover">' : '')
                + '</div>';
            var wishlistBtn = '<button class="wishlist-btn' + (wishlistEnabled && r.wishlisted ? ' wishlisted' : '') + '" data-product-id="' + escapeHtml(r.id) + '" type="button" aria-label="Save to wishlist">'
                + '<svg width="20" height="20" viewBox="0 0 64 64" fill="currentColor"><path d="M32,57C31,56.5 5,42 5,23.5C5,13.8 12.2,6.5 21,6.5C26,6.5 30.4,9 32,11.2C33.6,9 38,6.5 43,6.5C51.8,6.5 59,13.8 59,23.5C59,42 33,56.5 32,57Z"/></svg>'
                + '</button>';

            return '<div class="product-card-wrap">'
                + '<a href="' + url + '?back=' + encodeURIComponent(config.currentPagePath) + '" class="search-suggest-item">'
                + imgWrap
                + '<div class="search-suggest-info">'
                + '<span class="search-suggest-name">' + name + '</span>'
                + '<span class="search-suggest-price">$' + price + '</span>'
                + '</div>'
                + '</a>'
                + wishlistBtn
                + '</div>';
        }).join('');

        var viewAll = '<a href="' + config.searchUrl + '?q=' + encodeURIComponent(q) + '" class="search-view-all">'
            + 'View all results for &ldquo;' + escapeHtml(q) + '&rdquo;'
            + '</a>';

        box.innerHTML = '<div class="search-suggest-grid">' + items + '</div>' + viewAll;
    }

    function initDataTableSearchClear() {
        document.querySelectorAll('.dt-search input[type="search"]').forEach(function (input) {
            var wrap = document.createElement('div');
            wrap.className = 'dt-search-wrap';
            input.parentNode.insertBefore(wrap, input);
            wrap.appendChild(input);

            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'dt-search-clear';
            btn.setAttribute('aria-label', 'Clear search');
            btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
            wrap.appendChild(btn);

            function update() {
                wrap.classList.toggle('has-value', input.value.length > 0);
            }

            input.addEventListener('input', update);
            btn.addEventListener('click', function () {
                input.value = '';
                input.dispatchEvent(new Event('input', { bubbles: true }));
                update();
                input.focus();
            });

            update();
        });
    }

    function initWishlistButtons() {
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.wishlist-btn');
            if (!btn) return;

            e.preventDefault();
            e.stopPropagation();

            var id = btn.dataset.productId;
            var wasWishlisted = btn.classList.contains('wishlisted');
            btn.disabled = true;

            fetch(config.wishlistToggleUrl + id, {
                method: 'POST',
                headers: { 'X-CSRF-Token': config.csrfToken }
            })
                .then(function (r) {
                    if (!r.ok) throw new Error('Wishlist request failed');
                    return r.json();
                })
                .then(function (data) {
                    btn.classList.toggle('wishlisted', data.wishlisted);
                    if (data.guest) showWishlistToast();
                    updateWishlistBadge(data.wishlisted ? 1 : -1);
                })
                .catch(function (err) {
                    btn.classList.toggle('wishlisted', wasWishlisted);
                    console.error('Wishlist error:', err);
                    window.alert('Could not update your wishlist. Please try again.');
                })
                .finally(function () { btn.disabled = false; });
        });
    }

    function updateWishlistBadge(delta) {
        var wrap = document.querySelector('a[title="Wishlist"].nav-cart-wrap');
        if (!wrap) return;

        var badge = wrap.querySelector('.nav-cart-badge');
        var current = badge ? parseInt(badge.textContent, 10) : 0;
        var next = Math.max(0, current + delta);

        if (next > 0) {
            if (badge) {
                badge.textContent = next;
            } else {
                badge = document.createElement('span');
                badge.className = 'nav-cart-badge';
                badge.textContent = next;
                wrap.appendChild(badge);
            }
        } else if (badge) {
            badge.remove();
        }
    }

    function showWishlistToast() {
        var existing = getById('wishlist-guest-toast');
        if (existing) existing.remove();

        var toast = document.createElement('div');
        toast.id = 'wishlist-guest-toast';
        toast.innerHTML = 'To save your wishlist please <a href="' + config.loginUrl + '">login</a> or <a href="' + config.registerUrl + '">sign up</a>.';
        document.body.appendChild(toast);

        setTimeout(function () { toast.classList.add('show'); }, 10);
        setTimeout(function () {
            toast.classList.remove('show');
            setTimeout(function () { toast.remove(); }, 300);
        }, 3500);
    }

    function initNewsletter() {
        var form = getById('footerNewsletterForm');
        var thanks = getById('newsletterThanks');
        if (!form || !thanks) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            form.style.display = 'none';
            thanks.style.display = 'block';
        });
    }

    function init() {
        initNavbarScroll();
        initSearch();
        initDataTableSearchClear();
        initWishlistButtons();
        initNewsletter();

        document.addEventListener('click', closeNavMenus);

        var userBtn = getById('navUserBtn');
        var hamburger = getById('navHamburger');
        if (userBtn) userBtn.addEventListener('click', toggleNavDropdown);
        if (hamburger) hamburger.addEventListener('click', toggleMobileMenu);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
}());
