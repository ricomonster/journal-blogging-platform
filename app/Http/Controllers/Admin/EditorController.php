<?php

namespace Journal\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;

class EditorController extends Controller
{
    public function index()
    {
        return view('admin.editor.index', ['postId' => null]);
    }

    public function edit($postId)
    {
        return view('admin.editor.index', compact('postId'));
    }
}
