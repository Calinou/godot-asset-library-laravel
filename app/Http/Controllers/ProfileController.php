<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the form used to change the logged in user's name and password.
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        return view('profile.edit', ['user' => $user]);
    }

    /**
     * Update the logged in user's name and password.
     *
     * TODO: Implement changing the email address.
     * This will require the user to confirm their email address again.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // We need to do the validation here to pass the current user for the
        // name uniqueness check. We can't pass it to a form request as the user
        // isn't part of the controller signature (since it's it's not part of the URL).
        $input = $request->validate([
            'name' => [
                'required',
                'string',
                'max:'.User::NAME_MAX_LENGTH,
                Rule::unique('users')->ignore($user),
            ],
            'current_password' => 'nullable|required_with:new_password|string|password',
            'new_password' => 'nullable|string|min:'.User::PASSWORD_MIN_LENGTH.'|confirmed|different:current_password',
        ]);

        $user->name = $input['name'];

        if ($input['new_password']) {
            // User is changing their password
            $user->password = Hash::make($input['new_password']);
        }

        $user->save();

        $request->session()->flash('statusType', 'success');
        $request->session()->flash(
            'status',
            __('Your information has been updated!')
        );

        return redirect(route('profile.edit'));
    }
}
