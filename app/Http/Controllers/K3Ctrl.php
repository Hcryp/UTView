<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;

class K3Ctrl extends Controller {
    public function index() { return view('k3.dash'); }
}