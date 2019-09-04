<?php

namespace App\Http\Controllers\Api\v1;
use \App\Http\Controllers\Controller;

class AssetController extends Controller
{
    public function show(int $id)
    {
        return ['name' => $id];
    }
}
