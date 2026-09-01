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
});
