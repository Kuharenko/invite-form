<?php

namespace App\Providers;

use App\Contracts\PaymentInterface;
use App\Services\Payments\MonoPay;
use App\Services\Payments\PrivatePay;
use Illuminate\Foundation\Application;
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
       app()->bind(PaymentInterface::class, function ($app) {
           $provider = request()->provider;
           if (($provider ?? '') === PrivatePay::type) {
               return new PrivatePay();
           }
           return new MonoPay();
       });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
