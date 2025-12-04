<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;

class WikiCtrl extends Controller {
    public function index() { return view('wiki.index', ['topics' => ['Safety', 'Ops', 'Env']]); }
    public function show($slug) { return view('wiki.show', ['slug' => $slug]); }
}