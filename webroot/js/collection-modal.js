/**
 * Open/close behavior for the shared #collectionModal.
 * Pages opt-in by setting `window.collectionModalTriggerId` to the id
 * of the button that should open the modal (e.g. 'explore-btn',
 * 'continueShoppingBtn'). Multiple ids may be provided as an array.
 */
(function () {
    var FOCUSABLE = 'a[href], button:not([disabled]), input:not([disabled]), [tabindex]:not([tabindex="-1"])';
    var DRAG_CLOSE_DISTANCE = 90;

    function init() {
        var modal = document.getElementById('collectionModal');
        if (!modal) return;

        var content = modal.querySelector('.collection-modal-content');
        var header = modal.querySelector('.collection-modal-header');
        var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
        var lastTrigger = null;
        var closeTimer = null;

        function isOpen() {
            return modal.classList.contains('is-open') && !modal.classList.contains('is-closing');
        }

        function open(event) {
            if (isOpen()) return;
            clearTimeout(closeTimer);
            lastTrigger = event && event.currentTarget ? event.currentTarget : document.activeElement;
            modal.classList.remove('is-closing');
            modal.classList.add('is-open');
            document.body.classList.add('collection-modal-open');
            content.style.transform = '';
            content.focus({ preventScroll: true });
        }

        function finishClose() {
            modal.classList.remove('is-open', 'is-closing');
            content.style.transform = '';
            document.body.classList.remove('collection-modal-open');
            if (lastTrigger && typeof lastTrigger.focus === 'function') {
                lastTrigger.focus({ preventScroll: true });
            }
        }

        function close() {
            if (!isOpen()) return;
            if (reducedMotion.matches) {
                finishClose();
                return;
            }
            modal.classList.add('is-closing');
            closeTimer = setTimeout(finishClose, 200);
        }

        function trapTab(event) {
            var focusables = Array.prototype.filter.call(
                content.querySelectorAll(FOCUSABLE),
                function (el) { return el.offsetParent !== null; }
            );
            if (!focusables.length) return;

            var first = focusables[0];
            var last = focusables[focusables.length - 1];
            var active = document.activeElement;

            if (event.shiftKey && (active === first || active === content)) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && active === last) {
                event.preventDefault();
                first.focus();
            }
        }

        var triggerIds = window.collectionModalTriggerId || [];
        if (typeof triggerIds === 'string') triggerIds = [triggerIds];

        triggerIds.forEach(function (id) {
            var btn = document.getElementById(id);
            if (btn) {
                btn.setAttribute('aria-haspopup', 'dialog');
                btn.addEventListener('click', open);
            }
        });

        modal.querySelectorAll('[data-modal-close]').forEach(function (el) {
            el.addEventListener('click', close);
        });

        document.addEventListener('keydown', function (event) {
            if (!isOpen()) return;
            if (event.key === 'Escape') {
                event.preventDefault();
                close();
            } else if (event.key === 'Tab') {
                trapTab(event);
            }
        });

        // Swipe the header down to close on phones
        var dragStartY = null;
        var dragDistance = 0;

        header.addEventListener('pointerdown', function (event) {
            if (event.pointerType === 'mouse' || !window.matchMedia('(max-width: 600px)').matches) return;
            dragStartY = event.clientY;
            dragDistance = 0;
            content.classList.add('is-dragging');
            header.setPointerCapture(event.pointerId);
        });

        header.addEventListener('pointermove', function (event) {
            if (dragStartY === null) return;
            dragDistance = Math.max(0, event.clientY - dragStartY);
            content.style.transform = 'translateY(' + dragDistance + 'px)';
        });

        function endDrag() {
            if (dragStartY === null) return;
            dragStartY = null;
            content.classList.remove('is-dragging');
            if (dragDistance > DRAG_CLOSE_DISTANCE) {
                close();
            } else {
                content.style.transform = '';
            }
        }

        header.addEventListener('pointerup', endDrag);
        header.addEventListener('pointercancel', endDrag);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
