<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\{
    Model,
    Relations\BelongsToMany,
    Relations\HasMany,
};

/**
 * @method static firstWhere(string $string, string $getText)
 * @method static find(string $getCallbackDataByKey)

 * @property mixed id
 */
class Club extends Model
{

    protected $table = 'club';
    protected $fillable = ['title', 'club_description', 'game_description'];

    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'club_admin')->using(ClubAdmin::class)->withTimestamps();
    }

    public function events(): HasMany
    {
        return $this->hasMany(ClubEvent::class, 'club_id', 'id');
    }

    public function getDatesArray(): array
    {
        $dates = [];
        $periods = CarbonPeriod::create(Carbon::today(), Carbon::today()->addDays(6));
        foreach ($periods as $period) {
            $text = $date = $period->format('d.m');
            if ($this->events()->where('date', $date)->first()) {
                $text .= ' 📄';
            }

            $dates[] = [
                'text' => $text,
                'callback' => [
                    'id'   => $this->id,
                    'date' => $date,
                    'a'    => 'clubEventByDate',
                ]
            ];
        }

        return $dates;
    }

}
