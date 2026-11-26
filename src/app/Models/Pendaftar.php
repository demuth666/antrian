<?php

namespace App\Models;

use App\Enums\JenisKelamin;
use Database\Factories\PendaftarFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'nik',
    'nik_hash',
    'hp_hash',
    'nama',
    'tanggal_lahir',
    'jenis_kelamin',
    'no_hp',
    'alamat',
    'jenis_disabilitas',
])]
#[Hidden(['nik', 'no_hp'])]
class Pendaftar extends Model
{
    /** @use HasFactory<PendaftarFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nik' => 'encrypted',
            'no_hp' => 'encrypted',
            'tanggal_lahir' => 'date',
            'jenis_kelamin' => JenisKelamin::class,
        ];
    }

    /**
     * @return HasMany<Antrian, $this>
     */
    public function antrians(): HasMany
    {
        return $this->hasMany(Antrian::class);
    }

    protected function namaSamar(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $bagian = preg_split('/\s+/', trim($this->nama)) ?: [];

                if (count($bagian) <= 1) {
                    return $bagian[0] ?? '';
                }

                $inisial = Str::upper(Str::substr($bagian[count($bagian) - 1], 0, 1));

                return $bagian[0].' '.$inisial.'.';
            },
        );
    }
}
