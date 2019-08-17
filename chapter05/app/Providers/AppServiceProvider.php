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
        $this->app->bind(
            \App\DataProvider\FavoriteRepositoryInterface::class, // インターフェイス(抽象クラス)
            \App\DataProvider\FavoriteRepository::class // 呼び出されるインスタンス(具象クラス)
        );
    }
}
