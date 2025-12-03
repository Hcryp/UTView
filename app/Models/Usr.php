<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usr extends Authenticatable {
    protected $table = 'users'; // Maps to default users table
}