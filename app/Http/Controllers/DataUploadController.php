<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required:max:255',
            'site' => 'required',
            'note' => 'required:max:255'
        ]);

        auth()->user()->files()->create([
            'title' => $request->get('title'),
            'site' => $request->get('site'),
            'note' => $request->get('note')
        ]);

        return back()->with('message', 'Your file is submitted Successfully');
    }}
