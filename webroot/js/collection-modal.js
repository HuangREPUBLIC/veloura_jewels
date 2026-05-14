/**
 * Open/close behavior for the shared #collectionModal.
 * Pages opt-in by setting `window.collectionModalTriggerId` to the id
 * of the button that should open the modal (e.g. 'explore-btn',
 * 'continueShoppingBtn'). Multiple ids may be provided as an array.
 */
(function () {
    function init() {
        var modal = document.getElementById('collectionModal');
        if (!modal) return;

        var overlay = modal.querySelector('.collection-modal-overlay');
        var closeBtn = modal.querySelector('.collection-modal-close');

        function open() { modal.classList.add('is-open'); }
        function close() { modal.classList.remove('is-open'); }

        var triggerIds = window.collectionModalTriggerId || [];
        if (typeof triggerIds === 'string') triggerIds = [triggerIds];

        triggerIds.forEach(function (id) {
            var btn = document.getElementById(id);
            if (btn) btn.addEventListener('click', open);
        });

        if (overlay) overlay.addEventListener('click', close);
        if (closeBtn) closeBtn.addEventListener('click', close);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') close();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
