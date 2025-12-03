<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wiki;

class AdmCtrl extends Controller
{
    public function dash()
    {
        $pages = Wiki::latest()->paginate(20);
        return view('admin.dashboard', compact('pages'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:wikis,slug',
            'content' => 'required',
        ]);

        Wiki::create($valid);
        return redirect()->route('admin.dash')->with('success', 'Page created.');
    }

    public function edit($id)
    {
        $page = Wiki::findOrFail($id);
        return view('admin.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = Wiki::findOrFail($id);
        
        $valid = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:wikis,slug,'.$id,
            'content' => 'required',
        ]);

        $page->update($valid);
        return redirect()->route('admin.dash')->with('success', 'Page updated.');
    }

    public function destroy($id)
    {
        Wiki::destroy($id);
        return back()->with('success', 'Page deleted.');
    }
}