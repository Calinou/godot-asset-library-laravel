<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ListUsers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminController extends Controller
{
    /**
     * Display a paginated list of users.
     */
    public function index(ListUsers $request): View
    {
        $validated = $request->validated();

        $itemsPerPage = $validated['max_results'] ?? 50;
        $page = $validated['page'] ?? 1;

        // Display the most recently registered users first as those are
        // more likely to require attention.
        $users = User::with(['assets', 'assetReviews'])->orderByDesc('created_at')->get();
        $paginator = new LengthAwarePaginator(
            $users->slice(intval(($page - 1) * $itemsPerPage), $itemsPerPage)->values(),
            $users->count(),
            $itemsPerPage
        );
        $paginator->withPath(route('admin.index'));

        return view('admin.index', ['users' => $paginator]);
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
            __('The user “:user” has been blocked!', ['user' => $user->username])
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
            __('The user “:user” has been unblocked!', ['user' => $user->username])
        );

        $admin = Auth::user();
        Log::info("$admin unblocked $user.");

        return redirect(route('admin.index'));
    }
}
