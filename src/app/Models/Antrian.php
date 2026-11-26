<?php

namespace App\Models;

use App\Enums\KategoriAntrian;
use App\Enums\StatusAntrian;
use Database\Factories\AntrianFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'pendaftar_id',
    'layanan_id',
    'loket_id',
    'petugas_id',
    'tanggal',
    'nomor',
    'kode',
    'kategori',
    'status',
    'nama_pendamping',
    'dipanggil_at',
    'mulai_dilayani_at',
    'selesai_at',
])]
class Antrian extends Model
{
    /** @use HasFactory<AntrianFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'nomor' => 'integer',
            'kategori' => KategoriAntrian::class,
            'status' => StatusAntrian::class,
            'dipanggil_at' => 'datetime',
            'mulai_dilayani_at' => 'datetime',
            'selesai_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'kode';
    }

    public function aktifHariIni(): bool
    {
        return in_array($this->status, StatusAntrian::aktif(), true)
            && $this->tanggal->isToday();
    }

    /**
     * @return BelongsTo<Pendaftar, $this>
     */
    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class);
    }

    /**
     * @return BelongsTo<Layanan, $this>
     */
    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }

    /**
     * @return BelongsTo<Loket, $this>
     */
    public function loket(): BelongsTo
    {
        return $this->belongsTo(Loket::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    /**
     * @return BelongsToMany<Bantuan, $this>
     */
    public function bantuans(): BelongsToMany
    {
        return $this->belongsToMany(Bantuan::class, 'antrian_bantuan');
    }

    /**
     * @return HasMany<AntrianLog, $this>
     */
    public function logs(): HasMany
    {
        return $this->hasMany(AntrianLog::class)->orderBy('created_at');
    }

    /**
     * @return HasOne<Ulasan, $this>
     */
    public function ulasan(): HasOne
    {
        return $this->hasOne(Ulasan::class);
    }

    protected function nomorTampil(): Attribute
    {
        return Attribute::make(
            get: fn (): string => sprintf('%s-%03d', $this->layanan?->prefix ?? '-', $this->nomor),
        );
    }

    protected function namaSamar(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->pendaftar?->nama_samar ?? '',
        );
    }
}
