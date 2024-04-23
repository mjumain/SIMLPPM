<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BukaPenerimaan;
use Helpers;
use App\TahunAnggaran;
use App\UnitKerja;
use App\JenisUsulan;
use App\Skim;
use App\SumberDana;

class BukaPenerimaanController extends Controller
{
  function __construct()
  {  
    $this->middleware('permission:read-buka-penerimaan')->only('index');
    $this->middleware('permission:create-buka-penerimaan')->only('store','create');
    $this->middleware('permission:update-buka-penerimaan')->only('edit','update');
    $this->middleware('permission:delete-buka-penerimaan')->only('destroy');
    
  }
   public function index(Request $r)
   {
      $sumber="";
      $unit_kerja="";
      $skim="";
      $datas=BukaPenerimaan::when($r->sumber_dana_id,function($q)use($r){
              $q->where('sumber_dana_id',$r->sumber_dana_id);
              $sumber=$r->sumber_dana_id;
            })
            ->when($r->unit_kerja_id,function($q)use($r){
              $q->where('unit_kerja_id',$r->sumber_dana_id);
              $unit_kerja=$r->unit_kerja_id;
            })
            ->when($r->skim_id,function($q)use($r){
              $q->where('skim_id',$r->skim_id);
              $skim=$r->skim_id;
            });

      if ($r->has('tahun_anggaran_id'))  
      {

        $ta=TahunAnggaran::find($r->tahun_anggaran_id);
        $datas=$datas->where('tahun_anggaran_id',$r->tahun_anggaran_id);
      }
      else {
        $ta=TahunAnggaran::where('status',1)->first();
       $datas=$datas->where('tahun_anggaran_id',$ta->id_tahun_anggaran);
      }

      $datas=$datas->orderBy('jadwal_tutup','desc')->get();
      return view('admin.setup-aplikasi.buka-penerimaan.buka-penerimaan-page',compact('datas','ta','sumber','unit_kerja','skim'));
   }

   public function create()
   {
    
    $ta=TahunAnggaran::orderBy('tahun','desc')->get();
    $unit_kerja=UnitKerja::get();
    $sumber_dana=SumberDana::get();
    $skim=Skim::get();
    $jenis_usulan=JenisUsulan::get();
    return view('admin.setup-aplikasi.buka-penerimaan.tambah-buka-penerimaan',compact('ta','unit_kerja','sumber_dana','skim','jenis_usulan'));
   }
   public function store(Request $r)
   {
    BukaPenerimaan::create($r->all());
    Helpers::log("Membuka penerimaan");
    Helpers::alert('success','Berhasil buka penerimaan');
    return redirect('buka-penerimaan');
   }

   public function edit($id)
   {
    $data=BukaPenerimaan::find($id);
    $ta=TahunAnggaran::orderBy('tahun','desc')->get();
    $unit_kerja=UnitKerja::get();
    $sumber_dana=SumberDana::get();
    $skim=Skim::get();
    $jenis_usulan=JenisUsulan::get();
    return view('admin.setup-aplikasi.buka-penerimaan.edit-buka-penerimaan-page',compact('ta','unit_kerja','sumber_dana','skim','jenis_usulan','data'));
   }
   public function update(Request $r, $id)
   {
    BukaPenerimaan::where('id_buka_penerimaan',$id)->update($r->except('_token','_method'));
    Helpers::alert('success','Berhasil edit penerimaan');
    Helpers::log("Merubah data buka penerimaan");
    return redirect('buka-penerimaan');
   }
   public function destroy($id)
   {
    $cek=BukaPenerimaan::find($id);
    if ($cek->usulan->count()) {
      Helpers::alert('danger','Gagal hapus karena sudah terkait dengan usulan kegiatan penelitian/pengabdian');
      return back();
    }
    Helpers::log("Menghapus data buka penerimaan");
    $cek->delete();
    Helpers::alert('success','Berhasil hapus data');
    return back();
   }
}
