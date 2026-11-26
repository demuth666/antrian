<?php

namespace App\Models;

use Database\Factories\LayananFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama', 'prefix', 'aktif'])]
class Layanan extends Model
{
    /** @use HasFactory<LayananFactory> */
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
     * @return HasMany<Antrian, $this>
     */
    public function antrians(): HasMany
    {
        return $this->hasMany(Antrian::class);
    }

    /**
     * @return BelongsToMany<Loket, $this>
     */
    public function lokets(): BelongsToMany
    {
        return $this->belongsToMany(Loket::class, 'loket_layanan');
    }
}
