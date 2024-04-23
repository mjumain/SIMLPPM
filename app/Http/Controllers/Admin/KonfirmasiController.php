<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use Uang;
use DB;
use DateTime;
use App\User;
use Tanggal;
use App\Pegawai;
use Helpers;
use App\BukaPenerimaan;
use App\Pelaksana;
use App\Usulan;
class KonfirmasiController extends Controller
{
   function __construct()
  {  
    $this->middleware('permission:read-konfirmasi')->only('index');
    $this->middleware('permission:update-konfirmasi')->only('konfirmasi');
   
  }
   public function index(Request $request)
   {
    $ta=Helpers::tahun_anggaran_aktif();
    $tawaran=Pelaksana::where('id_peg',auth()->user()->id_peg)
              ->where('jabatan','anggota')
              
              ->whereHas('usulan',function($q)use($ta){
                $q->whereHas('buka_penerimaan',function($r)use($ta){
                  $r->where('tahun_anggaran_id',$ta->id_tahun_anggaran);
                });
              })
              ->get();
      return view('admin.konfirmasi.konfirmasi-page',compact('tawaran'));
   }

   public function konfirmasi($id,$konfirmasi)
   {
     $pelaksana=Pelaksana::find($id);
     $pelaksana->konfirmasi=$konfirmasi;
     $pelaksana->tgl_konfirmasi=date('Y-m-d H:i:s');
     $pelaksana->save();
     $pelaksana=Pelaksana::find($id);
     Helpers::log("Mengkonfirmasi ".strtoupper($konfirmasi).' menjadi anggota usulan dengan judul '.$pelaksana->usulan->judul);
     Helpers::alert('success','Berhasil konfirmasi '.strtoupper($konfirmasi).' menjadi anggota usulan dengan judul '.$pelaksana->usulan->judul);
     return back();
   }
    

   


}
