<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        // return response()->json(['env' => env('API_Password'), 'req' => $request->api_password]);

        if ($request->api_password !== env('API_Password', 'x5VUNpan7jk')) {
            return responseJson(401, '', 'Unauthenticatd');

        }
        return $next($request);
    }
}
