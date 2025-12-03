<?php

namespace App\Http\Controllers;
use App\Models\Doc;

class PubCtrl extends Controller {
    public function index() {
        return view('pub.list', ['docs' => Doc::where('isPub', 1)->latest()->get()]);
    }
    public function read($slug) {
        return view('pub.read', ['doc' => Doc::where('slug', $slug)->where('isPub', 1)->firstOrFail()]);
    }
}