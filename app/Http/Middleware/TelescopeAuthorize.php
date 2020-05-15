<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Laravel\Telescope\Telescope;

class TelescopeAuthorize
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
        dd(Telescope::$authUsing);
        return Telescope::check($request) ? $next($request) : abort(403);
    }
}
