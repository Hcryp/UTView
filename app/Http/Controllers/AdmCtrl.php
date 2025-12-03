<?php namespace App\Http\Controllers;

use App\Models\Doc;
use Illuminate\Http\Request;

class AdmCtrl extends Controller {
    // Main Dashboard view
    public function dash() { return view('adm.dash', ['count' => Doc::count()]); }

    // Data Recapitulation view
    public function recap() { return view('adm.recap', ['stats' => Doc::recapData()]); }

    // CMS: Add new document
    public function add(Request $r) {
        Doc::create($r->validate(['title' => 'required', 'content' => 'required']));
        return back()->with('msg', 'Created');
    }

    // CMS: Modify existing document
    public function mod(Request $r, $id) {
        Doc::findOrFail($id)->update($r->all());
        return back()->with('msg', 'Updated');
    }

    // CMS: Delete document
    public function del($id) {
        Doc::destroy($id);
        return back()->with('msg', 'Deleted');
    }
}