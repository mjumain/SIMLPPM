<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Helpers;

use App\Bidang;
use App\Pegawai;
use App\JenisSkema;
use App\Wilayah;
use App\JenisUsulan;
use App\TahunAnggaran;
use App\Pelaksana;
class LoadDataController extends Controller
{
    public function loadTema($id)
    {
      $bidang=Bidang::find($id);
      $option="<option value=''>Pilih Tema</option>";
      foreach ($bidang->tema as $k) {
          $option.="<option value='".$k->id_tema."'>".$k->nama_tema."</option>";
      }
      return $option;
    }
    public function loadLuaranTKT($id)
    {
      $jenis_skema=JenisSkema::find($id);
      
      $option['tkt']="<option value=''>Pilih TKT</option>";
      foreach ($jenis_skema->tkt as $t) {
          $option['tkt'].="<option value='".$t->id_tkt."'>".$t->nilai_tkt.' '.$t->keterangan."</option>";
      }

      $option['luaran_wajib']="";
      foreach ($jenis_skema->luaran_wajib as $lj) {
          $option['luaran_wajib'].="<option value='".$lj->id_luaran."'>".$lj->nama_luaran."</option>";
      }
      $option['luaran_tambahan']="";
      foreach ($jenis_skema->luaran_tambahan as $lt) {
          $option['luaran_tambahan'].="<option value='".$lt->id_luaran."'>".$lt->nama_luaran."</option>";
      }
      return $option;
    }
    
    public function loadKecamatan(Request $request)
    {
        $datas=Wilayah::where('id_level_wilayah',3)->where('nama_wilayah','like','%'.$request->search.'%')->take(50)->get();
        $json=[];

        if ($datas) {
//          dd($datas);
          foreach ($datas as $data) {

             $json[] = ['id'=>$data->id_wilayah, 'text'=>$data->nama_wilayah.'  ('.$data->parent->nama_wilayah.' - '.$data->parent->parent->nama_wilayah.')'];
              
              
          }
        }
      return response()->json($json);
    }

    public function loadDosenPegawai(Request $request)
    {

      $datas=Pegawai::where(function($q)use ($request){
              $q->where('nip','like','%'.$request->search.'%')
              ->orWhere('nama_lengkap','like','%'.$request->search.'%');

            })->take(50)->get();

        $json=[];
        if ($datas) {
          
          foreach ($datas as $data) {

             $json[] = ['id'=>$data->id_pegawai, 'text'=>$data->nip." - ".Helpers::nama_gelar($data) ];
              
              
          }
        }

      return response()->json($json);
    }
    public function cekDosenTersedia(Request $request){
    
      $status="";
      $boleh=1;
      if (auth()->user()->pegawai->id_pegawai==$request->id_peg) {
            
        $status='Maaf!, Tidak boleh memasukan diri sendiri sebagai anggota';
        $boleh=0;
        return response()->json(['status'=>$status,'boleh'=>$boleh]);
            
      }
      else if (!in_array($request->skim_id,[3,4])) {
        $cek=Helpers::cek_jumlah_kegiatan($request->jenis_usulan_id,$request->id_peg);
        if ($cek['izin']==false) {
          $status=$cek['pesan'];
          $boleh=0;

        }
      }
      

      return response()->json(['status'=>$status,'boleh'=>$boleh]);



  }

  public function loadDataReviewer(Request $request)
  {
    $datas=Pegawai::where(function($q)use ($request){
              $q->where('nip','like','%'.$request->search.'%')
              ->orWhere('nama_lengkap','like','%'.$request->search.'%');

            })
            ->whereHas('reviewer_tahun_anggaran',function($q){
              $q->where('id_tahun_anggaran',Helpers::tahun_anggaran_aktif()->id_tahun_anggaran);
            })
            ->take(50)->get();

        $json=[];
        if ($datas) {
          
          foreach ($datas as $data) {

             $json[] = ['id'=>$data->id_pegawai, 'text'=>$data->nip." - ".Helpers::nama_gelar($data) ];
              
              
          }
        }

      return response()->json($json);
  }

}
