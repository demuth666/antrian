const toggle = document.querySelector('[data-nav-toggle]');

if (toggle) {
    const menu = document.getElementById(toggle.getAttribute('aria-controls'));
    const label = toggle.querySelector('[data-nav-label]');
    const mobile = window.matchMedia('(max-width: 960px)');

    const setOpen = (open) => {
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute(
            'aria-label',
            open ? toggle.dataset.ariaClose : toggle.dataset.ariaOpen,
        );
        menu?.classList.toggle('is-open', open);

        if (label) {
            label.textContent = open ? toggle.dataset.labelClose : toggle.dataset.labelOpen;
        }
    };

    const isOpen = () => toggle.getAttribute('aria-expanded') === 'true';

    toggle.addEventListener('click', () => setOpen(!isOpen()));

    menu?.addEventListener('click', (event) => {
        if (event.target.closest('a, button')) {
            setOpen(false);
        }
    });

    document.addEventListener('click', (event) => {
        if (isOpen() && !toggle.contains(event.target) && !menu?.contains(event.target)) {
            setOpen(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isOpen()) {
            setOpen(false);
            toggle.focus();
        }
    });

    mobile.addEventListener('change', (event) => {
        if (!event.matches) {
            setOpen(false);
        }
    });
}
