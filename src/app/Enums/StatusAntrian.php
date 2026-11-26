<?php

namespace App\Enums;

enum StatusAntrian: string
{
    case Menunggu = 'menunggu';
    case Dipanggil = 'dipanggil';
    case Dilayani = 'dilayani';
    case Selesai = 'selesai';
    case TidakHadir = 'tidak_hadir';
    case Batal = 'batal';

    public function label(): string
    {
        return __('enums.statuses.'.$this->value);
    }

    public function pesan(?string $loket = null): string
    {
        $tujuan = $loket ?: __('queue.counter');

        return match ($this) {
            self::Menunggu => __('queue.status_messages.waiting'),
            self::Dipanggil => __('queue.status_messages.called', ['counter' => $tujuan]),
            self::Dilayani => __('queue.status_messages.served'),
            self::Selesai => __('queue.status_messages.done'),
            self::TidakHadir => __('queue.status_messages.absent'),
            self::Batal => __('queue.status_messages.cancelled'),
        };
    }

    public function bolehTransisiKe(self $tujuan): bool
    {
        return match ($this) {
            self::Menunggu => in_array($tujuan, [self::Dipanggil, self::Batal], true),
            self::Dipanggil => in_array($tujuan, [self::Dilayani, self::TidakHadir, self::Batal], true),
            self::Dilayani => $tujuan === self::Selesai,
            self::Selesai, self::TidakHadir, self::Batal => false,
        };
    }

    public function terminal(): bool
    {
        return in_array($this, [self::Selesai, self::TidakHadir, self::Batal], true);
    }

    /**
     * @return array<int, self>
     */
    public static function aktif(): array
    {
        return [self::Menunggu, self::Dipanggil, self::Dilayani];
    }
}
