<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
class LimitFormRepeatSubmit
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
        $token = $request->input('form_token');
        if (Cache::has($token)) {
            return response()->json(["repeat"=>1]);
        }

        Cache::put($token ,'value', 1);
        return $next($request);
    }
}
