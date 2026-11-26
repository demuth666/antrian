const DURATION = 6000;
const EXIT_DURATION = 220;

const dismissToast = (toast) => {
    if (toast.classList.contains('is-leaving')) {
        return;
    }

    toast.classList.add('is-leaving');
    window.setTimeout(() => toast.remove(), EXIT_DURATION);
};

document.querySelectorAll('[data-toast]').forEach((toast) => {
    let timer = window.setTimeout(() => dismissToast(toast), DURATION);

    const pause = () => window.clearTimeout(timer);

    const resume = () => {
        pause();
        timer = window.setTimeout(() => dismissToast(toast), DURATION);
    };

    toast.addEventListener('mouseenter', pause);
    toast.addEventListener('mouseleave', resume);
    toast.addEventListener('focusin', pause);
    toast.addEventListener('focusout', resume);

    toast.querySelector('[data-toast-close]')?.addEventListener('click', () => {
        pause();
        dismissToast(toast);
    });
});
