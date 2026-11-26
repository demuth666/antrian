<?php

namespace App\Models;

use Database\Factories\BantuanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['kode', 'nama', 'aktif'])]
class Bantuan extends Model
{
    /** @use HasFactory<BantuanFactory> */
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
     * @return BelongsToMany<Antrian, $this>
     */
    public function antrians(): BelongsToMany
    {
        return $this->belongsToMany(Antrian::class, 'antrian_bantuan');
    }
}
