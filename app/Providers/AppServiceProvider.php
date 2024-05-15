<?php

namespace App\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $whitelist = array(
            '127.0.0.1',
            'localhost',
            '::1'
        );

        $url = substr(Request::root(), 7);
        $url = substr($url, 0, strrpos($url, ":", 0));


//        if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
        if(!in_array($url, $whitelist)){
            URL::forceScheme('https');
        }


    }
}
