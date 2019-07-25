<?php

namespace App\Http\Controllers;

class AssetController extends Controller
{
    public function index()
    {
        return ['hello' => 'world', 'foo' => 'bar'];
    }

    public function single(int $id)
    {
        return $id;
    }
}
