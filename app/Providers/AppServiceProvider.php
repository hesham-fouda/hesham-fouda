<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;


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
        Validator::extend('eg_phone_number', function($attribute, $value, $parameters, $validator){
            return preg_match('/^(00201|201|\+201|01)(0|1|2|5)([0-9]{8})$/', $value);
        });
    }
}
