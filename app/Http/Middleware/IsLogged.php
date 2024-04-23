<?php

namespace App\Http\Middleware;

use Closure;
use Helpers;
class IsLogged
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
        if (auth()->check()==0) {
            Helpers::alert('warning','Anda harus login terlebih dahulu');
            return redirect('/');
        }
        return $next($request);
    }
}
