const root = document.querySelector('[data-antrian]');

if (root?.dataset.kode) {
    try {
        localStorage.setItem('antrian_kode_terakhir', root.dataset.kode);
    } catch {
        // localStorage tidak tersedia (misalnya mode privat ketat).
    }
}

if (root && root.dataset.pollUrl) {
    const pollUrl = root.dataset.pollUrl;
    const statusPill = document.querySelector('#status-pill');
    const statusMessage = document.querySelector('#status-pesan');
    const remaining = document.querySelector('#q-sisa');
    const kelasStatus = {
        dipanggil: 'warn',
        dilayani: 'ok',
        selesai: 'ok',
        tidak_hadir: 'bad',
        batal: 'dim',
    };

    let berhenti = root.dataset.terminal === '1';
    let sedangMemuat = false;
    let timer = null;

    const perbaruiLoket = (lokets) => {
        (lokets ?? []).forEach((loket) => {
            const nomor = document.querySelector(`[data-loket-id="${loket.id}"] b`);

            if (!nomor) {
                return;
            }

            if (nomor.textContent !== loket.nomor_tampil) {
                nomor.textContent = loket.nomor_tampil;
            }

            if (loket.nomor === null) {
                nomor.removeAttribute('data-nomor');
            } else {
                nomor.dataset.nomor = String(loket.nomor);
            }
        });
    };

    const perbaruiTiket = (tiket) => {
        if (statusPill) {
            const kelasBaru = ['stat-t', kelasStatus[tiket.status] ?? ''].filter(Boolean).join(' ');

            if (statusPill.className !== kelasBaru) {
                statusPill.className = kelasBaru;
            }

            if (statusPill.textContent !== tiket.status_label) {
                statusPill.textContent = tiket.status_label;
            }
        }

        if (statusMessage && statusMessage.textContent !== tiket.pesan) {
            statusMessage.textContent = tiket.pesan;
        }

        const sisaBaru = tiket.sisa_teks ?? '';

        if (remaining && remaining.textContent !== sisaBaru) {
            remaining.textContent = sisaBaru;
        }

        if (tiket.terminal) {
            berhenti = true;
            clearInterval(timer);
        }
    };

    const polling = async () => {
        if (berhenti || sedangMemuat) {
            return;
        }

        sedangMemuat = true;

        try {
            const response = await fetch(pollUrl, {
                headers: { Accept: 'application/json' },
                cache: 'no-store',
            });

            if (!response.ok) {
                return;
            }

            const data = await response.json();

            perbaruiLoket(data.loket);

            if (data.tiket) {
                perbaruiTiket(data.tiket);
            }
        } catch {
            // Jaringan sementara terputus: coba lagi pada interval berikutnya.
        } finally {
            sedangMemuat = false;
        }
    };

    if (!berhenti) {
        timer = setInterval(polling, 5000);
    }
}
