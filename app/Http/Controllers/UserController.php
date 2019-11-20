<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{
    /**
     * Display information about an user.
     */
    public function show(User $user)
    {
        $user->load('assets.versions');

        // Only display published assets, with top-scoring assets first
        $user->assets = $user->assets->where('is_published', true)->sortByDesc('score');

        return view('user.show', ['user' => $user]);
    }
}
