<?php

namespace App\Models\Fights;

use Illuminate\Database\Eloquent\Model;

class RobotFightResults extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fight_id', 'winner_id', 'loser_id'
    ];
}