<?php

namespace App\Http\Middleware;

use Closure;
use Helpers;
class DataPendukung
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
        
        
        if (!auth()->user()->pegawai->data_pendukung&&auth()->user()->jenis_akun=='dosen') {
            Helpers::alert('info',"Wajib isi data pendukung berupa nomor rekening dan NPWP terlebih dahulu klik tombol <b> Lengkapi Data Pendukung</b>");
            return redirect('data-diri');
        }

        return $next($request);
        
    }
}
