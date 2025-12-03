<?php

namespace App\Models;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory};

class Doc extends Model {
    use HasFactory;
    protected $fillable = ['title', 'slug', 'summary', 'content', 'isPub'];
}