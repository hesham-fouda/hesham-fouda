<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('test', function () {
    /** @var $user \App\CoAccount **/
    $user = \App\CoAccount::query()->find(1);



    dd($user);
});

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

