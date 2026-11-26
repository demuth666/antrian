<?php

namespace App\Support;

final class Identitas
{
    public static function normalisasiNik(string $nik): string
    {
        return preg_replace('/\D+/', '', $nik) ?? '';
    }

    public static function normalisasiHp(string $hp): string
    {
        $hp = preg_replace('/\D+/', '', $hp) ?? '';

        if (str_starts_with($hp, '0')) {
            return '62'.substr($hp, 1);
        }

        return $hp;
    }

    public static function hash(string $nilai): string
    {
        return hash_hmac('sha256', $nilai, (string) config('app.key'));
    }

    public static function hashNik(string $nik): string
    {
        return self::hash(self::normalisasiNik($nik));
    }

    public static function hashHp(string $hp): string
    {
        return self::hash(self::normalisasiHp($hp));
    }
}
