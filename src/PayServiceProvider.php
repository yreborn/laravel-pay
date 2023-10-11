<?php

namespace Yreborn\LaravelPay;

use Illuminate\Support\ServiceProvider;

class PayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('pay', function ($app) {
            return new Pay($app['config']);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/pay.php' => config_path('pay.php'),
        ]);
    }
}
