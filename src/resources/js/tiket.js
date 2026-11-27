const tombolUnduh = document.querySelector('[data-unduh-tiket]');
const qrSvg = document.querySelector('#qr svg');

if (tombolUnduh && qrSvg) {
    const i18n = (() => {
        try {
            return JSON.parse(tombolUnduh.dataset.i18n || '{}');
        } catch {
            return {};
        }
    })();

    const t = (key, fallback) => i18n[key] || fallback;
    const labelAsli = tombolUnduh.textContent.trim() || t('download', 'Unduh tiket');
    let timerLabel = null;

    const teks = (selector) => document.querySelector(selector)?.textContent.trim() ?? '';

    const kembalikanLabel = (pesan) => {
        tombolUnduh.textContent = pesan;
        window.clearTimeout(timerLabel);
        timerLabel = window.setTimeout(() => {
            tombolUnduh.textContent = labelAsli;
        }, 2400);
    };

    const muatGambarQr = () => new Promise((resolve, reject) => {
        const gambar = new Image();

        gambar.addEventListener('load', () => resolve(gambar));
        gambar.addEventListener('error', () => reject(new Error('QR code tidak dapat dimuat.')));
        gambar.src = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(new XMLSerializer().serializeToString(qrSvg));
    });

    const kotakBulat = (konteks, x, y, lebar, tinggi, radius) => {
        const r = Math.min(radius, lebar / 2, tinggi / 2);

        konteks.beginPath();
        konteks.moveTo(x + r, y);
        konteks.arcTo(x + lebar, y, x + lebar, y + tinggi, r);
        konteks.arcTo(x + lebar, y + tinggi, x, y + tinggi, r);
        konteks.arcTo(x, y + tinggi, x, y, r);
        konteks.arcTo(x, y, x + lebar, y, r);
        konteks.closePath();
    };

    const potongTeks = (konteks, isi, lebarMaks) => {
        if (konteks.measureText(isi).width <= lebarMaks) {
            return isi;
        }

        let hasil = isi;

        while (hasil.length > 1 && konteks.measureText(`${hasil}…`).width > lebarMaks) {
            hasil = hasil.slice(0, -1);
        }

        return `${hasil}…`;
    };

    const tulisBaris = (konteks, isi, x, y, lebarMaks, tinggiBaris, maksBaris) => {
        const kata = isi.split(/\s+/).filter(Boolean);
        const baris = [];
        let sekarang = '';
        let terpotong = false;

        for (const bagian of kata) {
            const uji = sekarang ? `${sekarang} ${bagian}` : bagian;

            if (sekarang && konteks.measureText(uji).width > lebarMaks) {
                baris.push(sekarang);
                sekarang = bagian;

                if (baris.length === maksBaris) {
                    terpotong = true;
                    break;
                }
            } else {
                sekarang = uji;
            }
        }

        if (baris.length < maksBaris && sekarang) {
            baris.push(sekarang);
        }

        baris.forEach((isiBaris, indeks) => {
            const akhir = indeks === baris.length - 1;
            let isiAkhir = akhir && terpotong ? `${isiBaris}…` : isiBaris;

            if (akhir) {
                isiAkhir = potongTeks(konteks, isiAkhir, lebarMaks);
            }

            konteks.fillText(isiAkhir, x, y + indeks * tinggiBaris);
        });
    };

    const gambarTiket = async () => {
        const skala = 2;
        const lebar = 900;
        const tinggi = 1440;
        const kanvas = document.createElement('canvas');
        kanvas.width = lebar * skala;
        kanvas.height = tinggi * skala;

        const konteks = kanvas.getContext('2d');

        if (!konteks) {
            throw new Error('Kanvas tidak didukung peramban ini.');
        }

        konteks.scale(skala, skala);

        const qr = await muatGambarQr();
        const merek = document.querySelector('.nav .brand')?.textContent.trim() || 'SIAP RENTAN MPP SUMEDANG';
        const pil = document.querySelector('#status-pill');
        const kelas = pil?.className || '';

        const warna = {
            paper: '#FFFFFF',
            ink: '#0E3B3A',
            muted: '#4C6866',
            line: '#D3E5E0',
            perf: '#9CC4BD',
            accent: '#173A5E',
            green: '#1D6A3E',
            red: '#B3261E',
            amber: '#8A5A00',
            dim: '#7F9A96',
        };

        konteks.clearRect(0, 0, lebar, tinggi);
        kotakBulat(konteks, 1, 1, lebar - 2, tinggi - 2, 24);
        konteks.fillStyle = warna.paper;
        konteks.fill();
        konteks.strokeStyle = warna.line;
        konteks.lineWidth = 2;
        konteks.stroke();

        konteks.textAlign = 'left';
        konteks.textBaseline = 'alphabetic';
        konteks.fillStyle = warna.ink;
        konteks.font = '500 22px "Public Sans", system-ui, sans-serif';
        konteks.fillText(merek, 72, 82);

        konteks.fillStyle = warna.muted;
        konteks.font = '400 12px "IBM Plex Mono", monospace';
        konteks.fillText(t('ticket_label', 'Tiket antrian'), 72, 108);

        konteks.save();
        konteks.translate(760, 74);
        konteks.rotate(-3 * Math.PI / 180);
        konteks.strokeStyle = warna.accent;
        konteks.lineWidth = 1.5;
        konteks.strokeRect(-66, -18, 132, 36);
        konteks.fillStyle = warna.accent;
        konteks.font = '500 12px "IBM Plex Mono", monospace';
        konteks.textAlign = 'center';
        konteks.fillText(t('priority', 'Prioritas').toUpperCase(), 0, 4);
        konteks.restore();

        konteks.strokeStyle = warna.perf;
        konteks.lineWidth = 1;
        konteks.setLineDash([6, 6]);
        konteks.beginPath();
        konteks.moveTo(40, 132);
        konteks.lineTo(860, 132);
        konteks.stroke();
        konteks.setLineDash([]);

        konteks.textAlign = 'left';
        konteks.fillStyle = warna.muted;
        konteks.font = '400 12px "IBM Plex Mono", monospace';
        konteks.fillText(t('number_label', 'Nomor antrian'), 72, 178);

        const nomor = teks('#q-num') || '-';
        let ukuranNomor = 140;
        konteks.font = `500 ${ukuranNomor}px "IBM Plex Mono", monospace`;

        const lebarNomor = konteks.measureText(nomor).width;

        if (lebarNomor > 756) {
            ukuranNomor = Math.max(96, Math.floor(ukuranNomor * 756 / lebarNomor));
            konteks.font = `500 ${ukuranNomor}px "IBM Plex Mono", monospace`;
        }

        konteks.fillStyle = warna.accent;
        konteks.fillText(nomor, 72, 178 + 28 + ukuranNomor);

        const status = teks('#status-pill');
        const sisa = teks('#q-sisa');
        const yStatus = 392;
        let warnaDot = warna.accent;

        if (kelas.includes('ok')) {
            warnaDot = warna.green;
        } else if (kelas.includes('bad')) {
            warnaDot = warna.red;
        } else if (kelas.includes('dim')) {
            warnaDot = warna.dim;
        } else if (kelas.includes('warn')) {
            warnaDot = warna.amber;
        }

        konteks.beginPath();
        konteks.arc(76, yStatus - 6, 4, 0, Math.PI * 2);
        konteks.fillStyle = warnaDot;
        konteks.fill();

        konteks.fillStyle = warna.ink;
        konteks.font = '500 18px "Public Sans", system-ui, sans-serif';
        konteks.fillText(status, 92, yStatus);

        if (sisa) {
            const lebarStatus = konteks.measureText(status).width;
            konteks.fillStyle = warna.muted;
            konteks.font = '400 15px "Public Sans", system-ui, sans-serif';
            konteks.fillText(sisa, 92 + lebarStatus + 16, yStatus);
        }

        const pesan = teks('#status-pesan');

        if (pesan) {
            konteks.fillStyle = warna.muted;
            konteks.font = '400 16px "Public Sans", system-ui, sans-serif';
            tulisBaris(konteks, pesan, 72, 436, 756, 26, 2);
        }

        const sisiKotak = 292;
        const xKotak = (lebar - sisiKotak) / 2;
        const yKotak = 500;

        konteks.fillStyle = warna.paper;
        kotakBulat(konteks, xKotak, yKotak, sisiKotak, sisiKotak, 12);
        konteks.fill();
        konteks.strokeStyle = warna.line;
        konteks.lineWidth = 1;
        konteks.stroke();

        const sisiQr = 236;
        konteks.imageSmoothingEnabled = false;
        konteks.drawImage(qr, xKotak + (sisiKotak - sisiQr) / 2, yKotak + (sisiKotak - sisiQr) / 2, sisiQr, sisiQr);
        konteks.imageSmoothingEnabled = true;

        konteks.textAlign = 'center';
        konteks.fillStyle = warna.muted;
        konteks.font = '400 12px "IBM Plex Mono", monospace';
        konteks.fillText(t('scan', 'Pindai kode ini di loket'), lebar / 2, 828);

        konteks.textAlign = 'left';

        const detail = [
            [t('name', 'Nama'), teks('#q-nama')],
            [t('service', 'Layanan'), teks('#q-layanan')],
            [t('date', 'Tanggal'), teks('#q-tgl')],
        ];

        let yDetail = 900;

        for (const [label, nilai] of detail) {
            konteks.fillStyle = warna.muted;
            konteks.font = '400 11px "IBM Plex Mono", monospace';
            konteks.fillText(label, 72, yDetail);

            konteks.fillStyle = warna.ink;
            konteks.font = '500 18px "Public Sans", system-ui, sans-serif';
            konteks.fillText(potongTeks(konteks, nilai || '-', 756), 72, yDetail + 26);

            yDetail += 74;
        }

        konteks.strokeStyle = warna.perf;
        konteks.lineWidth = 1;
        konteks.setLineDash([8, 8]);
        konteks.beginPath();
        konteks.moveTo(40, 1150);
        konteks.lineTo(860, 1150);
        konteks.stroke();
        konteks.setLineDash([]);

        konteks.fillStyle = warna.muted;
        konteks.font = '400 14px "Public Sans", system-ui, sans-serif';
        tulisBaris(konteks, t('show', 'Tunjukkan tiket ini kepada petugas saat nomor Anda dipanggil'), 72, 1200, 756, 24, 2);

        konteks.fillStyle = warna.muted;
        konteks.font = '400 11px "IBM Plex Mono", monospace';
        konteks.fillText(t('save', 'Simpan gambar ini sebagai bukti antrian Anda'), 72, 1320);

        return kanvas;
    };

    const unduh = async () => {
        if (tombolUnduh.disabled) {
            return;
        }

        window.clearTimeout(timerLabel);
        tombolUnduh.disabled = true;
        tombolUnduh.textContent = t('preparing', 'Menyiapkan tiket');

        try {
            if (document.fonts?.load) {
                await Promise.allSettled([
                    document.fonts.load('500 140px "IBM Plex Mono"'),
                    document.fonts.load('400 12px "IBM Plex Mono"'),
                    document.fonts.load('400 16px "Public Sans"'),
                    document.fonts.load('500 18px "Public Sans"'),
                    document.fonts.load('500 22px "Public Sans"'),
                ]);
            }

            const kanvas = await gambarTiket();
            const blob = await new Promise((resolve, reject) => {
                kanvas.toBlob((hasil) => {
                    if (hasil) {
                        resolve(hasil);

                        return;
                    }

                    reject(new Error('Gambar tiket tidak dapat dibuat.'));
                }, 'image/png');
            });

            const nomor = teks('#q-num') || 'antrian';
            const namaBerkas = `${t('filename', 'tiket-antrian')}-${nomor.replace(/[^a-z0-9-]+/gi, '-').toLowerCase()}.png`;
            const tautan = document.createElement('a');

            tautan.href = URL.createObjectURL(blob);
            tautan.download = namaBerkas;
            document.body.append(tautan);
            tautan.click();
            tautan.remove();

            window.setTimeout(() => URL.revokeObjectURL(tautan.href), 1000);

            kembalikanLabel(t('downloaded', 'Tiket terunduh'));
        } catch (kesalahan) {
            console.error(kesalahan);
            kembalikanLabel(t('failed', 'Gagal mengunduh'));
        } finally {
            tombolUnduh.disabled = false;
        }
    };

    tombolUnduh.addEventListener('click', unduh);
}
