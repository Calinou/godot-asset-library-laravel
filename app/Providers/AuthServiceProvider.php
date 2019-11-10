<?php

declare(strict_types=1);

namespace App\Providers;

use App\User;
use App\Asset;
use App\AssetReview;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function (User $user) {
            return $user->is_admin;
        });

        Gate::define('edit-asset', function (User $user, Asset $asset) {
            return $user->is_admin || $user->id === $asset->author_id;
        });

        // To submit a review, an user must have a verified email.
        // Also, they can't review their own assets and can post only one review per asset.
        Gate::define('submit-review', function (User $user, Asset $asset) {
            if (! $user->hasVerifiedEmail() || $asset->author_id === $user->id) {
                return false;
            }

            foreach ($asset->reviews as $review) {
                if ($review->author_id === $user->id) {
                    return false;
                }
            }

            return true;
        });

        // To reply to a review, the user must have a verified email and must be the asset's author.
        // Also, they mustn't have already replied to the review.
        Gate::define('submit-review-reply', function (User $user, AssetReview $assetReview) {
            return
                $user->hasVerifiedEmail() &&
                $assetReview->asset->author_id === $user->id &&
                $assetReview->reply === null;
        });
    }
}
