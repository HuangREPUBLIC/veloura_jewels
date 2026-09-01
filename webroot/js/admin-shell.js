document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('adminSidebarToggle');
    var shell = document.querySelector('.admin-shell');
    var scrim = document.getElementById('adminSidebarScrim');

    // One class, two meanings depending on viewport (handled in CSS):
    // on desktop the sidebar is shown by default and this class collapses
    // it; on mobile it's off-canvas by default and this class slides it in.
    function setToggled(on) {
        shell.classList.toggle('admin-shell--nav-toggled', on);
        if (toggle) {
            toggle.setAttribute('aria-expanded', on ? 'true' : 'false');
        }
    }

    if (toggle && shell) {
        toggle.addEventListener('click', function () {
            setToggled(!shell.classList.contains('admin-shell--nav-toggled'));
        });
    }
    if (scrim && shell) {
        scrim.addEventListener('click', function () {
            setToggled(false);
        });
    }
    if (shell) {
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                setToggled(false);
            }
        });
    }

    // Shared delete-confirmation dialog for every [data-confirm-delete]
    // trigger across the admin pages, instead of the browser's native
    // confirm() popup.
    var dialog = document.getElementById('adminConfirmDelete');
    if (!dialog) {
        return;
    }
    var titleEl = document.getElementById('adminConfirmDeleteTitle');
    var bodyEl = document.getElementById('adminConfirmDeleteBody');
    var confirmBtn = document.getElementById('adminConfirmDeleteButton');
    var cancelBtn = dialog.querySelector('[data-confirm-cancel]');
    var csrfToken = document.body.dataset.csrfToken;
    var pendingUrl = null;

    document.addEventListener('click', function (event) {
        var trigger = event.target.closest('[data-confirm-delete]');
        if (!trigger) {
            return;
        }
        event.preventDefault();
        pendingUrl = trigger.getAttribute('data-delete-url');
        titleEl.textContent = trigger.getAttribute('data-confirm-title') || 'Delete this item?';
        bodyEl.textContent = trigger.getAttribute('data-confirm-body') || 'This cannot be undone.';
        dialog.showModal();
    });

    cancelBtn.addEventListener('click', function () {
        pendingUrl = null;
        dialog.close();
    });

    confirmBtn.addEventListener('click', function () {
        if (!pendingUrl) {
            return;
        }
        var form = document.createElement('form');
        form.method = 'post';
        form.action = pendingUrl;
        form.style.display = 'none';

        var methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);

        var csrfField = document.createElement('input');
        csrfField.type = 'hidden';
        csrfField.name = '_csrfToken';
        csrfField.value = csrfToken;
        form.appendChild(csrfField);

        document.body.appendChild(form);
        form.submit();
    });

    // Per-page select (templates/element/per_page_select.php): changing it
    // reloads the page with ?limit=N, resetting to page 1.
    var perPageSelect = document.querySelector('[data-per-page-select]');
    if (perPageSelect) {
        perPageSelect.addEventListener('change', function () {
            var url = new URL(window.location.href);
            url.searchParams.set('limit', perPageSelect.value);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        });
    }

    // Dashboard revenue chart tabs (templates/Users/dashboard.php): swap
    // which pre-rendered .revenue-chart panel is visible, no reload.
    var chartTabs = document.querySelectorAll('[data-chart-tab]');
    chartTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            var target = tab.getAttribute('data-chart-tab');
            chartTabs.forEach(function (t) {
                t.classList.toggle('is-active', t === tab);
                t.setAttribute('aria-selected', t === tab ? 'true' : 'false');
            });
            document.querySelectorAll('[data-chart-panel]').forEach(function (panel) {
                panel.hidden = panel.getAttribute('data-chart-panel') !== target;
            });
        });
    });

    // Topbar profile dropdown (templates/element/admin_topbar.php).
    var profileTrigger = document.querySelector('[data-profile-trigger]');
    var profileMenu = document.querySelector('[data-profile-menu]');
    if (profileTrigger && profileMenu) {
        profileTrigger.addEventListener('click', function (event) {
            event.stopPropagation();
            var isOpen = profileMenu.hidden === false;
            profileMenu.hidden = isOpen;
            profileTrigger.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
        });
        document.addEventListener('click', function (event) {
            if (!profileMenu.hidden && !profileMenu.contains(event.target) && event.target !== profileTrigger) {
                profileMenu.hidden = true;
                profileTrigger.setAttribute('aria-expanded', 'false');
            }
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !profileMenu.hidden) {
                profileMenu.hidden = true;
                profileTrigger.setAttribute('aria-expanded', 'false');
            }
        });
    }
});
