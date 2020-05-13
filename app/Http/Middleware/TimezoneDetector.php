<?php

namespace App\Http\Middleware;

use Closure;
use MaxMind\Db\Reader\InvalidDatabaseException;

class TimezoneDetector
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $reader = new \GeoIp2\Database\Reader(storage_path('app/GeoLite2-City.mmdb'));
            $record = $reader->city($request->ip());
            date_default_timezone_set($record->location->timeZone);
        } catch (\Exception $exception) {}

        return $next($request);
    }
}
