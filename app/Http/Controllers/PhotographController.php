<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class PhotographController extends Controller
{
    public function index()
    {
        return view('photographs.index');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png.jpg'
        ]);
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);
        return redirect()->route('photographs.index');
    }

}
