<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\User;

class AdminController extends Controller
{
    /**
     * Display a list of unapproved and approved users.
     */
    public function index()
    {
        $users = User::all();

        return view('admin.index', ['users' => $users]);
    }
}
