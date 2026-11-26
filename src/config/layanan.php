<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Identitas instansi
    |--------------------------------------------------------------------------
    |
    | Teks bertanda [kurung siku] pada desain dipusatkan di sini agar mudah
    | diganti tanpa menyunting banyak view.
    |
    */

    'instansi' => [
        'nama' => env('LAYANAN_NAMA_INSTANSI', '[nama instansi]'),
        'alamat' => env('LAYANAN_ALAMAT', '[alamat instansi]'),
        'telepon' => env('LAYANAN_TELEPON', '[nomor telepon]'),
        'email' => env('LAYANAN_EMAIL', '[email instansi]'),
        'jam' => env('LAYANAN_JAM', '[jam layanan]'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Pilihan jenis disabilitas
    |--------------------------------------------------------------------------
    |
    | Nilai ini disimpan pada kolom pendaftars.jenis_disabilitas. Hanya
    | ditampilkan kepada petugas yang berwenang.
    |
    */

    'jenis_disabilitas' => [
        'fisik' => 'Disabilitas fisik',
        'netra' => 'Disabilitas netra',
        'rungu_wicara' => 'Disabilitas rungu / wicara',
        'intelektual' => 'Disabilitas intelektual',
        'mental' => 'Disabilitas mental',
        'ganda' => 'Disabilitas ganda',
    ],

    /*
    |--------------------------------------------------------------------------
    | Akun admin awal
    |--------------------------------------------------------------------------
    |
    | Dipakai seeder. Jangan mengisi password langsung di kode.
    |
    */

    'admin' => [
        'nama' => env('ADMIN_NAME', 'Administrator'),
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD'),
    ],

];
