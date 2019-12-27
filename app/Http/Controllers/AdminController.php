<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminController extends Controller
{
    /**
     * Display a list of unapproved and approved users.
     */
    public function index(): View
    {
        // Display the most recently registered users first as those are
        // more likely to require attention.
        $users = User::all()->sortByDesc('created_at');

        return view('admin.index', ['users' => $users]);
    }

    /**
     * Block an user. Once the user is blocked, they can't participate anymore.
     */
    public function block(User $user, Request $request): RedirectResponse
    {
        $user->is_blocked = true;
        $user->save();

        $request->session()->flash('statusType', 'success');
        $request->session()->flash(
            'status',
            __('The user “:user” has been blocked!', ['user' => $user->name])
        );

        $admin = Auth::user();
        Log::info("$admin blocked $user.");

        return redirect(route('admin.index'));
    }

    /**
     * Unblock an user. The user may now participate again.
     */
    public function unblock(User $user, Request $request): RedirectResponse
    {
        $user->is_blocked = false;
        $user->save();

        $request->session()->flash('statusType', 'success');
        $request->session()->flash(
            'status',
            __('The user “:user” has been unblocked!', ['user' => $user->name])
        );

        $admin = Auth::user();
        Log::info("$admin unblocked $user.");

        return redirect(route('admin.index'));
    }
}
