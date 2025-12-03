<?php

namespace App\Http\Controllers;

use App\Models\Wiki;

class WikiCtrl extends Controller
{
    public function home()
    {
        // Fix: Fetch pages and pass to view
        $pages = Wiki::where('is_active', true)->latest()->simplePaginate(15);
        return view('wiki.home', compact('pages'));
    }

    public function index()
    {
        $pages = Wiki::where('is_active', true)->latest()->simplePaginate(15);
        return view('wiki.index', compact('pages'));
    }

    public function show($slug)
    {
        $page = Wiki::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('wiki.show', compact('page'));
    }
}