<?php

namespace App\Models\Fights;

use Illuminate\Database\Eloquent\Model;

class RobotFights extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'attacker_id', 'defender_id'
    ];
}