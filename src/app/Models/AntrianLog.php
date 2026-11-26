<?php

namespace App\Models;

use App\Enums\StatusAntrian;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['antrian_id', 'user_id', 'status_dari', 'status_ke', 'catatan', 'created_at'])]
class AntrianLog extends Model
{
    public $timestamps = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status_dari' => StatusAntrian::class,
            'status_ke' => StatusAntrian::class,
            'created_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Antrian, $this>
     */
    public function antrian(): BelongsTo
    {
        return $this->belongsTo(Antrian::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
