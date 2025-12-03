<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manhour extends Model
{
    protected $fillable = [
        'site', 'category', 'company', 'nrp', 'name', 
        'dept', 'position', 'contract_start', 'contract_end', 
        'work_days', 'manhours', 'is_active'
    ];

    protected $casts = [
        'contract_start' => 'date',
        'contract_end' => 'date',
    ];
}