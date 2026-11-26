const form = document.querySelector('#form-pendaftaran');

if (form) {
    const jenisRow = document.querySelector('#jenis-row');
    const jenisSelect = document.querySelector('#jenis');
    const error = document.querySelector('#err');

    const syncJenisDisabilitas = () => {
        const kategori = form.querySelector('input[name="kategori"]:checked')?.value;
        const isDisabilitas = kategori === 'disabilitas';

        jenisRow?.classList.toggle('hidden', !isDisabilitas);

        if (jenisSelect) {
            jenisSelect.required = isDisabilitas;
        }
    };

    form.addEventListener('change', syncJenisDisabilitas);

    form.addEventListener('submit', (event) => {
        if (!form.checkValidity()) {
            event.preventDefault();

            if (error) {
                error.textContent = form.dataset.msgIncomplete || '';
            }

            form.reportValidity();

            return;
        }

        const tombol = form.querySelector('button[type="submit"]');

        if (tombol) {
            tombol.disabled = true;
            tombol.textContent = form.dataset.msgProcessing || '';
        }
    });

    syncJenisDisabilitas();
}
