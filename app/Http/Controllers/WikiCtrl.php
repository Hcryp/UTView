<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page; // Assuming a simple Page model

class WikiCtrl extends Controller
{
    function home(){
        $pages = Page::where('active', 1)->get();
        return view('wiki.home', compact('pages'));
    }

    function read($slug){
        $page = Page::where('slug', $slug)->firstOrFail();
        return view('wiki.read', compact('page'));
    }
}