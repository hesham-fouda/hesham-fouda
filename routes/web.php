<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use GeoIp2\Database\Reader;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//

/*Route::get('/{vue_capture?}', function () {
    return view('index');
})->where('vue_capture', '[\/\w\.-]*');*/

//Route::get('test', function (\Illuminate\Http\Request $request) {
//    /** @var $user \App\CoAccount **/
//    $ip = '213.166.147.61';
//
//    $reader = new Reader(storage_path('app/GeoLite2-City.mmdb'));
//    $record = $reader->city($ip);
//
//    dd($record);
//    //$gi = geoip_open(storage_path('app/GeoIP.dat'), GEOIP_STANDARD);
//
//    dd([
//        'ip' => $ip,
//        'type' => 'ipv4',
//        'continent_code' => $record->continent->code,
//        'continent_name' => $record->continent->name,
//
//        'country_code' => $record->country->isoCode,
//        'country_name' => $record->country->name,
//        'region_code' => null,
//        'region_name' => null,
//        'city' => $record->city->name,
//        'zip' => null,
//        'latitude' => $record->location->latitude,
//        'longitude' => $record->location->longitude,
//        'time_zone' => $record->location->timeZone,
//    ]);
//    //dd(geoip($request->ip())->timezone);
//
//    $user = \App\CoAccount::query()->find(1);
//    dd($user->devices->first()->last_activity->timezone(app('user_timezone')));
//});

Route::get('x-logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('{slug?}', function () {
    return view('welcome');
})->where('slug', '^(?!api).*$');
//->where('slug', '[\/\w\.-]*');

Route::get(
    '/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');
Route::get('x-logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

