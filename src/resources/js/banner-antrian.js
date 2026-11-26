const KUNCI_TIKET = 'antrian_kode_terakhir';
const POLA_ULID = /^[0-9A-HJKMNP-TV-Z]{26}$/i;

const banner = document.querySelector('[data-antrian-banner]');

if (banner) {
    let kode = null;

    try {
        kode = localStorage.getItem(KUNCI_TIKET);
    } catch {
        // localStorage tidak tersedia (misalnya mode privat ketat).
    }

    if (kode && POLA_ULID.test(kode)) {
        const apiUrl = banner.dataset.apiUrl;
        const tiketUrl = banner.dataset.tiketUrl;
        const link = banner.querySelector('[data-antrian-banner-link]');
        const nomor = banner.querySelector('[data-antrian-banner-nomor]');

        fetch(`${apiUrl}?kode=${encodeURIComponent(kode)}`, {
            headers: { Accept: 'application/json' },
            cache: 'no-store',
        })
            .then((response) => {
                if (!response.ok) {
                    return null;
                }

                return response.json();
            })
            .then((data) => {
                if (!data?.tiket?.aktif) {
                    return;
                }

                if (link && tiketUrl) {
                    link.href = tiketUrl.replace('__KODE__', encodeURIComponent(kode));
                }

                if (nomor) {
                    nomor.textContent = ` (${data.tiket.nomor_tampil})`;
                }

                banner.hidden = false;
            })
            .catch(() => {
                // Tidak bisa memeriksa tiket sekarang; banner tetap tersembunyi.
            });
    }
}
