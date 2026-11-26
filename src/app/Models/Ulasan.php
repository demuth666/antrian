<?php

namespace App\Models;

use Database\Factories\UlasanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'antrian_id',
    'rating',
    'komentar',
])]
class Ulasan extends Model
{
    /** @use HasFactory<UlasanFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Antrian, $this>
     */
    public function antrian(): BelongsTo
    {
        return $this->belongsTo(Antrian::class);
    }
}
