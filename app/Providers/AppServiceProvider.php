<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Laravel\Telescope\Telescope;
use OwenIt\Auditing\Models\Audit;


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
        //date_default_timezone_set('Africa/cairo');
        /*App::singleton('user_timezone', function(){
            return 'Africa/cairo';
        });*/

        Telescope::auth(function ($request) {
            dd($request);
            return true;
        });

        Validator::extend('eg_phone_number', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(00201|201|\+201|01)(0|1|2|5)([0-9]{8})$/', $value);
        });

        Audit::creating(function (Audit $model) {
            return !(empty($model->old_values) && empty($model->new_values));
        });
    }
}
