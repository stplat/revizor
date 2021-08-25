<?php

namespace App\Providers;

use App\Http\ViewComposers\CategoriesComposer;
use App\Http\ViewComposers\HeadComposer;
use App\Http\ViewComposers\HeaderComposer;
use App\Http\ViewComposers\MenuComposer;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', HeadComposer::class);
    }
}
