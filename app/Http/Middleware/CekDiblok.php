<?php

namespace App\Http\Middleware;

use Closure;
use Helpers;
use App\Blokir;
class CekDiblok
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
        
        $cek=Blokir::where('id_peg',auth()->user()->pegawai->id_pegawai)->where('status_blokir',1)->first();
        if ($cek) {
            Helpers::alert('danger',"Anda sedang diblokir dan tidak bisa mengajukan usulan karena alasan <b>".$cek->alasan."</b>");
            return redirect('data-diri');
        }

        return $next($request);
        
    }
}
