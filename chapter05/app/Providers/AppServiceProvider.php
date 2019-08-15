<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $his->app->bind(
            \App\DataProvider\FavoriteRepositoryInterface::class,
            \App\DataProvider\FavoriteRepository::class
        );
    }
}
