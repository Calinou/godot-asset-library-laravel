<?php

namespace App\Http\Controllers;

class AssetController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function show(int $id)
    {
        return view('asset', ['id' => $id]);
    }
}
