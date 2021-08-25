<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
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
        require app_path('Helpers/JsonCyrylicToUtfTranslitHelper.php');
        require app_path('Helpers/RandomDateHelper.php');
        require app_path('Helpers/WhereBetweebArrayHelper.php');
        require app_path('Helpers/BoxTypesHelper.php');
        require app_path('Helpers/GetDaysBetweenDatesHelper.php');
    }
}
