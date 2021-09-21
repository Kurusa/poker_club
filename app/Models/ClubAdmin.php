<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    Pivot,
};

/**
 * @method static firstWhere(string $string, mixed $id)
 */
class ClubAdmin extends Pivot
{

    protected $table = 'club_admin';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

}
