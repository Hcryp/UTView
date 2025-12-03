<?php

namespace App\Http\Controllers;
use App\Models\Doc; use Illuminate\Http\Request;

class CmsCtrl extends Controller {
    public function index() { return view('cms.list', ['docs' => Doc::latest()->get()]); }
    
    public function create() { return view('cms.form'); }
    
    public function store(Request $req) {
        Doc::create($req->validate(['title'=>'required', 'slug'=>'required|unique:docs', 'content'=>'required', 'isPub'=>'boolean']));
        return redirect()->route('docs.index');
    }

    public function edit(Doc $doc) { return view('cms.form', ['doc' => $doc]); }
    
    public function update(Request $req, Doc $doc) {
        $doc->update($req->all()); // Add validation in production
        return redirect()->route('docs.index');
    }
    
    public function destroy(Doc $doc) { $doc->delete(); return back(); }
}