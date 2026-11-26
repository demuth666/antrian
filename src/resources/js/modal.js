const ready = (callback) => {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', callback, { once: true });
        return;
    }

    callback();
};

const openModal = (modal) => {
    if (!modal || modal.open) {
        return;
    }

    if (typeof modal.showModal === 'function') {
        modal.showModal();

        if (modal.hasAttribute('data-modal-focus-dialog')) {
            modal.focus();
        }

        return;
    }

    modal.setAttribute('open', '');
};

const closeModal = (modal) => {
    if (!modal) {
        return;
    }

    if (modal.open && typeof modal.close === 'function') {
        modal.close();
        return;
    }

    modal.removeAttribute('open');
};

const closestDialog = (element) => (
    element instanceof Element ? element.closest('dialog.modal') : null
);

document.addEventListener('click', (event) => {
    const target = event.target instanceof Element ? event.target : null;
    const opener = target ? target.closest('[data-modal-open]') : null;

    if (opener) {
        const dialog = document.getElementById(opener.getAttribute('data-modal-open'));

        if (dialog) {
            event.preventDefault();
            openModal(dialog);
        }

        return;
    }

    const closer = target ? target.closest('[data-modal-close]') : null;

    if (closer) {
        closeModal(closestDialog(closer));
        return;
    }

    const dialog = closestDialog(event.target);

    if (dialog && event.target === dialog) {
        closeModal(dialog);
    }
});

ready(() => {
    document.querySelectorAll('[data-modal-auto-open]').forEach((modal) => openModal(modal));

    const confirmModal = document.getElementById('konfirmasi');

    if (!confirmModal) {
        return;
    }

    const title = confirmModal.querySelector('.modal-title');
    const text = confirmModal.querySelector('.modal-text');
    const accept = confirmModal.querySelector('[data-confirm-accept]');
    let pendingForm = null;

    document.addEventListener('submit', (event) => {
        const form = event.target;

        if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-confirm')) {
            return;
        }

        event.preventDefault();
        pendingForm = form;

        if (title) {
            title.textContent = form.dataset.confirmTitle
                || confirmModal.dataset.defaultTitle
                || 'Hapus data ini?';
        }

        if (text) {
            text.textContent = form.dataset.confirmMessage
                || form.dataset.confirm
                || confirmModal.dataset.defaultMessage
                || 'Tindakan ini tidak dapat dibatalkan.';
        }

        if (accept) {
            accept.textContent = form.dataset.confirmButton
                || confirmModal.dataset.defaultButton
                || 'Hapus';
        }

        openModal(confirmModal);
    });

    accept?.addEventListener('click', () => {
        if (!pendingForm) {
            closeModal(confirmModal);
            return;
        }

        const form = pendingForm;
        const confirmMessage = form.getAttribute('data-confirm');
        pendingForm = null;

        closeModal(confirmModal);
        form.removeAttribute('data-confirm');

        try {
            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit();
            } else {
                form.submit();
            }
        } finally {
            if (confirmMessage !== null) {
                form.setAttribute('data-confirm', confirmMessage);
            }
        }
    });

    confirmModal.addEventListener('close', () => {
        pendingForm = null;
    });
});
