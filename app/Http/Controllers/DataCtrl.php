<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;

class DataCtrl extends Controller {
    // Main Dashboard View
    public function index() { 
        return view('dash.index'); 
    }
    
    // Data Manager View (K3/ESG List)
    public function manage() { 
        return view('dash.data'); 
    }
}