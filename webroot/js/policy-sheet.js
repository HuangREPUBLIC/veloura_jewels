// Opens policy links in a sheet so shoppers keep their place.
// Footer and client care pages still navigate to the full page.
(function () {
    var POLICY_PATH = /\/(privacy-policy|terms-and-conditions|refund-policy|shipping-policy)$/;
    var CLOSE_ICON = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
    var pages = {};
    var sheet, title, body, full;

    function build() {
        sheet = document.createElement('dialog');
        sheet.className = 'policy-sheet';
        sheet.setAttribute('aria-labelledby', 'policySheetTitle');
        sheet.innerHTML = '<header class="policy-sheet-head">'
            + '<h2 class="policy-sheet-title" id="policySheetTitle"></h2>'
            + '<button type="button" class="policy-sheet-close" aria-label="Close">' + CLOSE_ICON + '</button>'
            + '</header>'
            + '<div class="policy-sheet-body prose" tabindex="-1" autofocus></div>'
            + '<footer class="policy-sheet-foot"><a target="_blank" rel="noopener">Open full page</a></footer>';
        document.body.appendChild(sheet);

        title = sheet.querySelector('.policy-sheet-title');
        body = sheet.querySelector('.policy-sheet-body');
        full = sheet.querySelector('.policy-sheet-foot a');

        sheet.querySelector('.policy-sheet-close').addEventListener('click', function () { sheet.close(); });
        // A click on the dialog itself is a click on the backdrop.
        sheet.addEventListener('click', function (e) {
            if (e.target === sheet) sheet.close();
        });
        // Close here so Escape never reaches the cart drawer underneath.
        sheet.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            e.preventDefault();
            e.stopPropagation();
            sheet.close();
        });
        enableSwipe(sheet.querySelector('.policy-sheet-head'));
    }

    // Drag the handle down to dismiss the phone sheet.
    function enableSwipe(handle) {
        var startY = null;
        var phone = window.matchMedia('(max-width: 768px)');

        handle.addEventListener('touchstart', function (e) {
            if (!phone.matches) return;
            startY = e.touches[0].clientY;
            sheet.style.transition = 'none';
        }, { passive: true });

        handle.addEventListener('touchmove', function (e) {
            if (startY === null) return;
            sheet.style.transform = 'translateY(' + Math.max(0, e.touches[0].clientY - startY) + 'px)';
        }, { passive: true });

        handle.addEventListener('touchend', function (e) {
            if (startY === null) return;
            var distance = e.changedTouches[0].clientY - startY;
            startY = null;
            sheet.style.transition = '';
            if (distance > 90) sheet.close();
            sheet.style.transform = '';
        });
    }

    function load(path) {
        pages[path] = pages[path] || fetch(path, { credentials: 'same-origin' })
            .then(function (res) {
                if (!res.ok) throw new Error(res.status);
                return res.text();
            })
            .then(function (html) {
                return new DOMParser().parseFromString(html, 'text/html');
            });
        return pages[path];
    }

    function open(link) {
        if (!sheet) build();
        title.textContent = '';
        body.innerHTML = '<p class="policy-sheet-loading">Loading</p>';
        full.href = link.href;
        if (!sheet.open) sheet.showModal();

        load(link.pathname).then(function (doc) {
            var updated = doc.querySelector('.care-updated');
            title.textContent = doc.querySelector('.care-title').textContent;
            body.replaceChildren();
            if (updated) {
                var meta = document.createElement('p');
                meta.className = 'policy-sheet-meta';
                meta.textContent = updated.textContent;
                body.append(meta);
            }
            doc.querySelectorAll('.care-main > :not(.care-help)').forEach(function (el) {
                body.append(document.importNode(el, true));
            });
            var target = link.hash && body.querySelector(link.hash);
            if (target) target.scrollIntoView();
            else body.scrollTop = 0;
        }).catch(function () {
            window.location.href = link.href;
        });
    }

    document.addEventListener('click', function (e) {
        var link = e.target.closest('a[href]');
        if (!link || e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
        if (link.origin !== window.location.origin || !POLICY_PATH.test(link.pathname)) return;
        if (link.closest('.care, .footer')) return;
        e.preventDefault();
        open(link);
    });
})();
