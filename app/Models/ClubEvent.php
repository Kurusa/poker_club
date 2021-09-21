<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Model,
    Relations\BelongsTo,
    Relations\HasMany,
};

/**
 * @method static where(string $string, $id)
 * @method static find(string $eventId)
 *
 * @property mixed user_id
 * @property mixed club_id
 * @property mixed|string status
 * @property mixed|string date
 */
class ClubEvent extends Model
{

    protected $table = 'club_event';
    protected $fillable = ['club_id', 'description', 'date', 'status', 'user_id'];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function decisions(): HasMany
    {
        return $this->hasMany(UserEventDecision::class, 'event_id', 'id');
    }

}
