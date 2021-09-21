<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Model,
    Relations\BelongsTo,
};

/**
 * @method static updateOrCreate(array $array, array $array1)
 * @method static where(string $string, mixed $club_id)
 */
class UserEventDecision extends Model
{

    protected $table = 'user_event_decision';
    protected $fillable = ['user_id', 'event_id', 'club_id', 'value'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(ClubEvent::class, 'event_id', 'id');
    }

}
