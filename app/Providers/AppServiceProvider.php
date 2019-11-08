<?php

declare(strict_types=1);

namespace App\Providers;

use App\AssetReview;
use Illuminate\Pagination\Paginator;
use App\Observers\AssetReviewObserver;
use Illuminate\Support\ServiceProvider;

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
    }
}
