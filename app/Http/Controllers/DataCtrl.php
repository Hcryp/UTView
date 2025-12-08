<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataCtrl extends Controller
{
    public function index()
    {
        return view('dash.index');
    }

    public function manage()
    {
        return view('dash.data');
    }
}