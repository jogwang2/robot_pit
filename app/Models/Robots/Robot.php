<?php

namespace App\Models\Robots;

use Illuminate\Database\Eloquent\Model;

class Robot extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'speed', 'weight', 'power'
    ];
}