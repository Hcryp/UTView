<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManpowerLog extends Model
{
    protected $fillable = ['log_date', 'content', 'total_mp', 'total_mh'];

    protected $casts = [
        'log_date' => 'date',
        'content' => 'array'
    ];
}