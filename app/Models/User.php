<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Model,
    Relations\BelongsToMany,
    Relations\HasMany,
};

/**
 * @method static firstOrCreate(array $array, array $array1)
 * @method static where(string $string, int $getId)
 *
 * @property int chat_id
 * @property mixed first_name
 * @property mixed user_name
 * @property mixed status
 * @property mixed is_super_admin
 */
class User extends Model
{

    protected $table = 'user';
    protected $fillable = ['chat_id', 'first_name', 'user_name', 'status', 'is_super_admin'];

    public function club(): BelongsToMany
    {
        return $this->belongsToMany(Club::class, 'club_admin');
    }

    public function eventDecisions(): HasMany
    {
        return $this->hasMany(UserEventDecision::class, 'user_id', 'id');
    }

    public function getNameAttribute()
    {
        return $this->user_name ?: $this->first_name;
    }

}
