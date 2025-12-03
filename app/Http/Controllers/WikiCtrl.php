<?php namespace App\Http\Controllers;

use App\Models\Doc;
use Illuminate\Http\Request;

class WikiCtrl extends Controller {
    // Display public wiki home with latest docs
    public function idx() { return view('wiki.home', ['docs' => Doc::latest()->get()]); }

    // Read a specific document by slug
    public function read($slug) { return view('wiki.read', ['doc' => Doc::where('slug', $slug)->firstOrFail()]); }

    // Simple search functionality
    public function find(Request $r) { return view('wiki.find', ['res' => Doc::where('title', 'like', "%{$r->q}%")->get()]); }
}