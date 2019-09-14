<?php

namespace App\Http\Controllers\Api\v1;

use App\Asset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $assets = Asset::filterSearch($request);

        return ['result' => $assets];
    }

    public function show(int $id)
    {
        return ['name' => $id];
    }
}
