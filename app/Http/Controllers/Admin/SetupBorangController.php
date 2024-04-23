<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Borang;
use App\SkorBorang;
use App\JenisSkema;
use Helpers;
use App\TahunAnggaran;
use App\Usulan;
class SetupBorangController extends Controller
{
  function __construct()
  {  
    $this->middleware('permission:read-borang')->only('index','inputBorang');
    $this->middleware('permission:create-borang')->only('createInputBorang','storeInputBorang');
    $this->middleware('permission:update-borang')->only('editInputBorang','updateInputBorang');
    $this->middleware('permission:delete-borang')->only('destroyInputBorang','destroySkorBorang');
    
  }
   public function index()
   {
      $datas=JenisSkema::all();
      return view('admin.setup-aplikasi.borang.borang-page',compact('datas'));
   }
   public function inputBorang($tahap,$id_skema)
   {
      $judul_tahap=ucwords(str_replace('-',' ',$tahap));
      $jenis_skema=JenisSkema::find($id_skema);
      if ($tahap=='proposal') {
        $borang=$jenis_skema->borang_proposal;
      } else {
        $borang=$jenis_skema->borang_evaluasi_hasil;
      }
     return view('admin.setup-aplikasi.borang.input-borang-page',compact('tahap','judul_tahap','jenis_skema','borang'));
   }

   public function createInputBorang($tahap,$id_skema)
   {
      $judul_tahap=ucwords(str_replace('-',' ',$tahap));
      $jenis_skema=JenisSkema::find($id_skema);
     return view('admin.setup-aplikasi.borang.tambah-input-borang-page',compact('tahap','judul_tahap','jenis_skema'));
   }
   public function storeInputBorang(Request $request,$tahap,$id_skema)
   {
      $ta=TahunAnggaran::where('status',1)->first()->id_tahun_anggaran;
      $borang=Borang::create([
                'komponen_penilaian'=>$request->komponen_penilaian,
                'bobot'=>$request->bobot,
                'tahap'=>$tahap,
                'jenis_skema_id'=>$id_skema,
                'tahun_anggaran_id'=>$ta,
              ]);
      for($i=0; $i < count($request->skor); $i++)
      {
        SkorBorang::create([
          'borang_id'=>$borang->id_borang,
          'skor'=>$request['skor'][$i],
          'keterangan'=>$request['keterangan'][$i],

        ]);
      }
      Helpers::alert('success','Berhasil Tambah Komponen Penilaian');
      
      Helpers::log('Menambah '.$request->komponen_penilaian.' sebagai Komponen Penilaian');
     return redirect('setup-borang/input-borang/'.$tahap.'/'.$id_skema);
   }

   public function editInputBorang($tahap,$id_skema,$id_borang)
   {
      $judul_tahap=ucwords(str_replace('-',' ',$tahap));
      $jenis_skema=JenisSkema::find($id_skema);
      $borang=Borang::find($id_borang);
     return view('admin.setup-aplikasi.borang.edit-input-borang-page',compact('tahap','judul_tahap','jenis_skema','borang'));
   }
   public function updateInputBorang(Request $request,$tahap,$id_skema,$id_borang)
   {
      
    $borang=Borang::find($id_borang);
    for ($i=0; $i < count($request->id_skor_borang);$i++) {
      SkorBorang::find($request['id_skor_borang'][$i])
        ->update([
          'skor'=>$request['skor'][$i],
          'keterangan'=>$request['keterangan'][$i],
        ]);
    }

    if ($request->has('skor_baru')) {
      for ($i=0; $i < count($request->skor_baru) ; $i++) { 
        SkorBorang::create([
          'borang_id'=>$borang->id_borang,
          'skor'=>$request['skor_baru'][$i],
          'keterangan'=>$request['keterangan_baru'][$i],
        ]);
      }
    }
    $borang->update($request->only('komponen_penilaian','bobot'));
    Helpers::alert('success','Berhasil edit komponen penilaian borang');
    Helpers::log('Merubah komponen penilaian '.$request->komponen_penilaian);
     return redirect('setup-borang/input-borang/'.$tahap.'/'.$id_skema);
   }

   public function destroyInputBorang($tahap,$id_skema,$id_borang)
   {
      $cek=Borang::find($id_borang);
      if ($cek->usulan->count() > 0) {
        Helpers::alert('danger','Gagal hapus borang karena sudah terkait dengan usulan penelitian/pengabdian');
        return back();
      }
      Helpers::log('menghapus komponen penilaian '.$cek->komponen_penilaian);
      $cek->delete();
      Helpers::alert('success','Berhasil hapus komponen borang ');
      return back();

   }
   public function destroySkorBorang($id_skor)
   {
      $cek=SkorBorang::find($id_skor);
      if ($cek->usulan->count() > 0) {
        Helpers::alert('danger','Gagal hapus skor borang karena sudah terkait dengan usulan penelitian/pengabdian');
        return back();
      }

      $cek->delete();
      Helpers::alert('success','Berhasil hapus skor borang ');
      return back();

   }
}
