<?php

declare(strict_types=1);

namespace App\Providers;

use App\Asset;
use App\AssetReview;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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

        Gate::define('submit-asset', function (User $user) {
            return ! $user->is_blocked;
        });

        Gate::define('edit-asset', function (User $user, Asset $asset) {
            return ! $user->is_blocked && ($user->is_admin || $user->id === $asset->author_id);
        });

        // To submit a review, an user must have a verified email.
        // Also, they can't review their own assets and can post only one review per asset.
        // Archived assets also can't receive any further reviews.
        Gate::define('submit-review', function (User $user, Asset $asset) {
            if (
                $user->is_blocked ||
                ! $user->hasVerifiedEmail() ||
                $asset->author_id === $user->id ||
                $asset->is_archived
            ) {
                return false;
            }

            foreach ($asset->reviews as $review) {
                if ($review->author_id === $user->id) {
                    return false;
                }
            }

            return true;
        });

        // To edit or remove a review, an user must have posted the review or be an administrator.
        Gate::define('edit-review', function (User $user, AssetReview $assetReview) {
            return ! $user->is_blocked && ($user->is_admin || $user->id === $assetReview->author->id);
        });

        // To reply to a review, the user must have a verified email and must be the asset's author.
        // Also, they mustn't have already replied to the review.
        Gate::define('submit-review-reply', function (User $user, AssetReview $assetReview) {
            return
                ! $user->is_blocked &&
                $user->hasVerifiedEmail() &&
                $assetReview->asset->author_id === $user->id &&
                $assetReview->reply === null;
        });

        // To (un)block an user, the user must be an administrator and must not be
        // carrying out the action against another administrator.
        Gate::define('block-user', function (User $user, User $targetUser) {
            return $user->is_admin && ! $targetUser->is_admin;
        });
    }
}
