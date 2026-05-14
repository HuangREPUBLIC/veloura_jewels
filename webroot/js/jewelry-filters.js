/**
 * Filter dropdown toggle for jewelry/home_decor listing pages.
 * Opens one dropdown at a time, closes on outside click or Escape.
 */
(function () {
    function init() {
        var dropdowns = document.querySelectorAll('.jewelry-page .filter-dropdown');
        if (!dropdowns.length) return;

        function closeDropdown(dd) {
            dd.classList.remove('open');
            var btn = dd.querySelector('.filter-dropdown-btn');
            if (btn) btn.setAttribute('aria-expanded', 'false');
        }

        dropdowns.forEach(function (dd) {
            var btn = dd.querySelector('.filter-dropdown-btn');
            if (!btn) return;
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var isOpen = dd.classList.contains('open');
                dropdowns.forEach(closeDropdown);
                if (!isOpen) {
                    dd.classList.add('open');
                    btn.setAttribute('aria-expanded', 'true');
                }
            });
        });

        document.addEventListener('click', function () {
            dropdowns.forEach(closeDropdown);
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') dropdowns.forEach(closeDropdown);
        });

        document.querySelectorAll('.jewelry-page .filter-dropdown-menu').forEach(function (menu) {
            menu.addEventListener('click', function (e) { e.stopPropagation(); });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
