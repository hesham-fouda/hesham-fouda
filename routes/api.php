<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('ipv4', function (Request $request) {
    try {
        $reader = new \GeoIp2\Database\Reader(storage_path('app/GeoLite2-City.mmdb'));
        $record = $reader->city($request->ip());
        return [
            'ip' => $request->ip(),
            'type' => 'ipv4',
            'continent_code' => $record->continent->code,
            'continent_name' => $record->continent->name,

            'country_code' => $record->country->isoCode,
            'country_name' => $record->country->name,
            'region_code' => null,
            'region_name' => null,
            'city' => $record->city->name,
            'zip' => $record->postal->code,
            'latitude' => $record->location->latitude,
            'longitude' => $record->location->longitude,
            'time_zone' => $record->location->timeZone,
        ];
    } catch (Exception $exception) {
        dd($exception);
        return [
            'ip' => null,
            'type' => null,
            'continent_code' => null,
            'continent_name' => null,
            'country_code' => null,
            'country_name' => null,
            'region_code' => null,
            'region_name' => null,
            'city' => null,
            'zip' => null,
            'latitude' => null,
            'longitude' => null,
            'time_zone' => null,
        ];
    }
});
Route::post('co-login-check', 'CoAccountAuthController@CoLoginCheck');
Route::post('co-login', 'CoAccountAuthController@CoLogin');
