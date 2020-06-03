<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display information about an user.
     */
    public function show(User $user): View
    {
        $user->load('assets.versions');

        // Only display published assets, with top-scoring assets first
        $user->assets = $user->assets->where('is_published', true)->sortByDesc('score');

        return view('user.show', ['user' => $user]);
    }

    /**
     * List an user's reviews.
     */
    public function indexReviews(User $user): View
    {
        $user->assetReviews = $user->assetReviews->load('asset')->sortByDesc('created_at');

        return view('user.reviews.index', ['user' => $user]);
    }
}
