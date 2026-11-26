<?php

return [

    'nav' => [
        'today' => 'Antrian hari ini',
        'find_ticket' => 'Cari tiket',
    ],

    'dashboard' => [
        'title' => 'Antrian hari ini',
        'kicker' => 'Panel petugas',
        'call_next' => 'Panggil berikutnya',
        'find_ticket' => 'Cari tiket warga',
        'no_counter' => 'Akun Anda belum ditugaskan ke loket. Hubungi admin agar dapat mulai melayani antrian.',
        'no_services' => ':counter belum memiliki layanan. Hubungi admin untuk mengatur layanan loket.',
        'total_today' => '{0} :total antrian|{1} :total antrian|[2,*] :total antrian',
        'active_title' => 'Di loket',
        'waiting_title' => 'Menunggu',
        'closed_title' => 'Ditutup',
        'arrival_order' => 'Urut kedatangan',
        'empty_desk' => 'Belum ada warga di loket',
        'empty_page_title' => 'Loket masih kosong',
        'empty_page_body' => 'Antrian warga akan muncul di sini setelah mereka mendaftar.',
        'waiting_empty' => 'Tidak ada warga yang menunggu',
        'closed_empty' => 'Belum ada antrian yang ditutup hari ini',
        'table_aria' => 'Daftar antrian yang menunggu',
        'headers' => [
            'queue' => 'Antrian',
            'resident' => 'Warga',
            'since' => 'Sejak',
            'actions' => 'Aksi',
        ],
        'needs' => 'Kebutuhan',
        'needs_none' => 'Tidak ada',
        'companion' => 'Pendamping',
        'called_at' => 'Dipanggil :time',
        'serving_since' => 'Dilayani sejak :time',
        'start' => 'Mulai layani',
        'absent' => 'Tidak hadir',
        'finish' => 'Selesai',
        'cancel' => 'Batal',
    ],

    'find' => [
        'title' => 'Cari tiket',
        'kicker' => 'Panel petugas',
        'heading' => 'Cari tiket warga',
        'description' => 'Gunakan NIK untuk menemukan antrian hari ini saat warga kehilangan tiketnya.',
        'back' => 'Antrian hari ini',
        'nik_hint' => '16 digit',
        'note' => 'NIK tidak ditampilkan kembali di halaman ini.',
        'submit' => 'Cari tiket',
        'result_title' => 'Hasil pencarian',
        'empty' => 'Tidak ada antrian hari ini untuk NIK tersebut. Pastikan NIK sudah benar.',
        'table_aria' => 'Hasil pencarian tiket',
        'headers' => [
            'queue' => 'Antrian',
            'service' => 'Layanan',
            'status' => 'Status',
            'actions' => 'Aksi',
        ],
        'open' => 'Buka tiket',
    ],

    'flash' => [
        'no_queue' => 'Tidak ada antrian yang menunggu.',
        'no_counter' => 'Akun Anda belum ditugaskan ke loket.',
        'no_counter_services' => 'Akun petugas belum ditugaskan ke loket.',
        'called' => ':number dipanggil ke :counter.',
        'started' => 'mulai dilayani.',
        'finished' => 'telah selesai dilayani.',
        'absent' => 'ditandai tidak hadir.',
        'cancelled' => 'dibatalkan.',
    ],

];
