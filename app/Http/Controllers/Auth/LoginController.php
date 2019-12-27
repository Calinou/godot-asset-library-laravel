<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the OAuth2 provider's authentication page.
     */
    public function redirectToProvider(string $provider): RedirectResponse
    {
        if ($provider === 'gitlab') {
            // Only request the `read_user` scope if authenticating with GitLab
            return Socialite::driver($provider)->scopes(['read_user'])->redirect();
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the OAuth2 provider.
     */
    public function handleProviderCallback(string $provider): RedirectResponse
    {
        $userSocial = Socialite::driver($provider)->user();

        // Check if the user is already registered
        $currentUser = User::where(['email' => $userSocial->getEmail()])->first();

        if ($currentUser) {
            // Log in as the existing user
            Auth::login($currentUser);
        } else {
            // Register a new account and log in.
            // The email address is always considered verified,
            // as the OAuth2 provider has most likely verified it before.
            $user = new User();
            $user->fill([
                'name' => $userSocial->getName(),
                'email' => $userSocial->getEmail(),
                'provider' => $provider,
                'provider_id' => $userSocial->getId(),
            ])->markEmailAsVerified();

            Log::info("$user registered a new user account using the \"$provider\" OAuth2 provider.");
            Auth::login($user);
        }

        return redirect(route('asset.index'));
    }
}
