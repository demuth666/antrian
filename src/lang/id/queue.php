<?php

return [

    'counter' => 'loket',

    'status_messages' => [
        'waiting' => 'Menunggu giliran. Pantau layar dan halaman ini.',
        'called' => 'Nomor Anda dipanggil. Silakan menuju :counter.',
        'served' => 'Anda sedang dilayani. Jika perlu bantuan, hubungi petugas.',
        'done' => 'Layanan selesai. Terima kasih.',
        'absent' => 'Anda tidak hadir saat dipanggil. Silakan hubungi petugas.',
        'cancelled' => 'Antrian dibatalkan. Silakan hubungi petugas.',
    ],

    'remaining' => ':count antrian lagi',
    'next_in_line' => 'Anda berikutnya, mohon bersiap',
    'transition_denied' => 'Status antrian tidak dapat diubah dari :from ke :to.',
    'registration_log' => 'Pendaftaran dibuat',
    'status_log' => 'Status diubah menjadi :status.',

    'show' => [
        'title' => 'Tiket antrian :number',
        'description' => 'Nomor antrian dan QR code layanan prioritas Anda.',
        'kicker' => 'Layanan prioritas',
        'heading' => 'Tiket antrian',
        'lead' => 'Simpan halaman ini atau unduh tiketnya. Tunjukkan nomor atau QR code saat giliran Anda tiba.',
        'download' => 'Unduh tiket',
        'new_registration' => 'Pendaftaran baru',
    ],

    'ticket' => [
        'aria' => 'Tiket Anda',
        'qr_aria' => 'QR code nomor antrian :number',
        'number_label' => 'Nomor antrian',
        'priority' => 'Prioritas',
        'scan' => 'Pindai kode ini di loket',
        'name' => 'Nama',
        'service' => 'Layanan',
        'date' => 'Tanggal',
    ],

    'now_serving' => [
        'aria' => 'Sedang dilayani',
        'kicker' => 'Sedang dilayani',
        'empty' => 'Belum ada loket yang aktif',
        'note' => 'Diperbarui otomatis setiap 5 detik',
    ],

    'check' => [
        'title' => 'Cek antrian',
        'description' => 'Temukan tiket antrian Anda dengan NIK dan nomor HP yang dipakai saat mendaftar.',
        'kicker' => 'Cek antrian',
        'heading_before' => 'Temukan ',
        'heading_highlight' => 'tiket',
        'heading_after' => ' Anda.',
        'lead' => 'Masukkan NIK dan nomor HP yang dipakai saat mendaftar. Kami akan membuka tiket yang masih aktif hari ini.',
        'meta' => 'Tanpa akun · Hanya tiket aktif hari ini',
        'form_label' => 'Formulir penelusuran',
        'stamp' => 'Cek tiket',
        'preview_aria' => 'Pratinjau tiket',
        'preview_label' => 'Tiket Anda',
        'preview_number_label' => 'Nomor antrian',
        'preview_note' => 'Nomor muncul di sini setelah data cocok.',
        'preview_status' => 'Hanya tiket aktif hari ini',
        'nik_hint' => '16 digit',
        'phone' => 'Nomor HP',
        'phone_hint' => 'saat mendaftar',
        'submit' => 'Cek antrian saya',
        'help' => 'Tetap tidak menemukan tiket? Hubungi petugas :instansi di :telepon. Belum mendaftar?',
        'help_fallback' => 'Tetap tidak menemukan tiket? Hubungi petugas di loket prioritas. Belum mendaftar?',
        'help_link' => 'Ambil nomor antrian',
        'new_registration' => 'Daftar baru',
        'found' => 'Tiket Anda ditemukan. Simpan halaman ini agar tiket tidak hilang.',
        'not_found' => 'Data tidak ditemukan. Periksa kembali NIK dan nomor HP Anda, lalu coba lagi.',
    ],

    'rate_limit' => [
        'minutes' => ':count menit',
        'seconds' => ':count detik',
        'message' => 'Terlalu banyak percobaan. Silakan coba lagi dalam :time.',
    ],

    'js' => [
        'ticket_label' => 'Tiket antrian',
        'number_label' => 'Nomor antrian',
        'scan' => 'Pindai kode ini di loket',
        'name' => 'Nama',
        'service' => 'Layanan',
        'date' => 'Tanggal',
        'show' => 'Tunjukkan tiket ini kepada petugas saat nomor Anda dipanggil',
        'save' => 'Simpan gambar ini sebagai bukti antrian Anda',
        'preparing' => 'Menyiapkan tiket',
        'downloaded' => 'Tiket terunduh',
        'failed' => 'Gagal mengunduh',
        'filename' => 'tiket-antrian',
    ],

];
