<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manpower extends Model
{
    use HasFactory;

    protected $fillable = [
        'site',
        'category',
        'company',
        'nrp',
        'name',
        'department',
        'role',
        'join_date',
        'end_date',
        'effective_days',
        'manhours',
        'date_out',
        'out_reason',
        'status'
    ];

    const CATEGORIES = [
        'KARYAWAN',
        'KONTRAKTOR GRUP ASTRA',
        'KONTRAKTOR NON GRUP ASTRA',
        'MAGANG/PKL/OJT/UT SCHOOL'
    ];

    const DEPARTMENTS = [
        'ADMINISTRATION',
        'CORPU AREA',
        'PARTS',
        'SERVICE'
    ];

    const OUT_REASONS = [
        'HABIS KONTRAK',
        'MUTASI',
        'RESIGN'
    ];

    public function getDashboardCategoryAttribute()
    {
        if ($this->category == 'KARYAWAN') return 'ut';
        if (str_contains($this->category, 'MAGANG') || str_contains($this->company, 'UT SCHOOL')) return 'ojt';
        return 'partner';
    }
}