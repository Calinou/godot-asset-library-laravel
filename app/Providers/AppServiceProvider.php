<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\View\Composers\AppViewComposer;
use App\Models\AssetReview;
use App\Observers\AssetReviewObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('vendor/pagination/asset-library');
        AssetReview::observe(AssetReviewObserver::class);

        View::composer(
            '*', AppViewComposer::class
        );
    }
}
