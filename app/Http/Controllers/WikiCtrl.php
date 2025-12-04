<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;

class WikiCtrl extends Controller {
    public function index() { return view('wiki.index', ['topics' => ['Safety', 'Ops']]); }
    public function show($slug) { return view('wiki.show', ['slug' => $slug]); }
    
    // Admin Manager View
    public function manage() { 
        return view('dash.wiki'); 
    }
}