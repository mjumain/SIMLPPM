<?php

namespace App\Http\Middleware;

use Closure;
use Helpers;
class GantiPassword
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
        
        
        if (auth()->user()->ganti_password==1) {
            Helpers::alert('info',"Wajib ganti password terlebih dahulu");
            return redirect('pengaturan-akun');
        }

        return $next($request);
        
    }
}
