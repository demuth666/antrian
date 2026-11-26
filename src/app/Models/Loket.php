<?php

namespace App\Models;

use Database\Factories\LoketFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama', 'aktif'])]
class Loket extends Model
{
    /** @use HasFactory<LoketFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    /**
     * @return BelongsToMany<Layanan, $this>
     */
    public function layanans(): BelongsToMany
    {
        return $this->belongsToMany(Layanan::class, 'loket_layanan');
    }

    /**
     * @return HasMany<Antrian, $this>
     */
    public function antrians(): HasMany
    {
        return $this->hasMany(Antrian::class);
    }

    /**
     * @return HasMany<User, $this>
     */
    public function petugas(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
