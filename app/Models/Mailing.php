<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed user_id
 * @property mixed|string status
 * @property mixed|string text
 */
class Mailing extends Model
{

    protected $table = 'mailing';
    protected $fillable = ['user_id', 'text', 'image', 'status', 'club_id'];

}
