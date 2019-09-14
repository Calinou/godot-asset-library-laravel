<?php

namespace App\Http\Controllers;

use App\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $assets = Asset::filterSearch($request);

        return view('index', ['assets' => $assets]);
    }

    public function show(int $id)
    {
        return view('asset', ['id' => $id]);
    }
}
