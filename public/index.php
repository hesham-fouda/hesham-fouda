<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

if(isset($_SERVER['HTTP_CF_VISITOR'])){
    $scheme = json_decode($_SERVER['HTTP_CF_VISITOR'])->scheme;
    if($scheme == 'https'){
        $_SERVER['HTTP_X_FORWARDED_PORT'] = 443;
        $_SERVER['HEADER_X_FORWARDED_PORT'] = 443;
        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
        $_SERVER['HEADER_X_FORWARDED_PROTO'] = 'https';
        $_SERVER['HTTPS'] = 'on';
    }
}if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ipAddresses = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $_SERVER['REMOTE_ADDR'] = trim(end($ipAddresses));
}

if(!isset($_SERVER['HEADER_X_FORWARDED_PORT']))
    $_SERVER['HEADER_X_FORWARDED_PORT'] = $_SERVER['HTTP_X_FORWARDED_PORT'];
if(!isset($_SERVER['HEADER_X_FORWARDED_PROTO']))
    $_SERVER['HEADER_X_FORWARDED_PROTO'] = $_SERVER['HTTP_X_FORWARDED_PROTO'];
if(($_SERVER['HTTP_X_FORWARDED_PORT'] == 443 || $_SERVER['HEADER_X_FORWARDED_PORT'] = 443) || $_SERVER['HTTPS'] != 'on')
    $_SERVER['HTTPS'] = 'on';





define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
