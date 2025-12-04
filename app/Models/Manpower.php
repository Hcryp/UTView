<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manpower extends Model
{
    use HasFactory;

    protected $fillable = [
        'site', 'category', 'company', 'nrp', 'name', 
        'role', 'join_date', 'end_date', 'manhours', 'status'
    ];

    // Helper scope to group categories into Dashboard labels
    public function getDashboardCategoryAttribute()
    {
        if ($this->category == 'KARYAWAN') return 'ut';
        if (str_contains($this->category, 'MAGANG') || str_contains($this->company, 'UT SCHOOL')) return 'ojt';
        return 'partner'; // All Contractors
    }
}