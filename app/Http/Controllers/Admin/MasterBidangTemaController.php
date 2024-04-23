<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Bidang;
use App\TahunAnggaran;
use Helpers;
use App\JenisUsulan;
use App\Tema;
class MasterBidangTemaController extends Controller
{

  
  function __construct()
  {  
    $this->middleware('permission:read-bidang')->only('index','show');
    $this->middleware('permission:create-bidang')->only('create','store');
    $this->middleware('permission:update-bidang')->only('edit','update');
    $this->middleware('permission:delete-bidang')->only('destroy');
    
    
  }
   public function index()
   {
      $ta=TahunAnggaran::where('status',1)->first()->id_tahun_anggaran;
      $bidang_penelitian=Bidang::where('jenis_usulan_id',1)->where('tahun_anggaran_id',$ta)->get();
      $bidang_pengabdian=Bidang::where('jenis_usulan_id',2)->where('tahun_anggaran_id',$ta)->get();
      
      return view('admin.master-data.bidang-tema.bidang-tema-page',compact('bidang_penelitian','bidang_pengabdian'));
   }

   public function create()
   {

      $jenis_usulan=JenisUsulan::get();
      return view('admin.master-data.bidang-tema.tambah-bidang-tema-page',compact('jenis_usulan'));
   }
   public function store(Request $r)
   {
    $insert=Bidang::create($r->except('nama_tema'));
    
    for ($i=0; $i < count($r->nama_tema); $i++) { 
      Tema::create([
        'bidang_id'=>$insert->id_bidang,
        'nama_tema'=>$r['nama_tema'][$i]
      ]);  
    }
    Helpers::alert('success','Berhasil tambah bidang dan tema');
    return redirect('master-bidang-tema');
   }

   public function edit($id)
   {
    $data=Bidang::find($id);
    $jenis_usulan=JenisUsulan::get();
     return view('admin.master-data.bidang-tema.edit-bidang-tema-page',compact('jenis_usulan','data'));
   }
   public function update(Request $r, $id)
   {
    Bidang::where('id_bidang',$id)->update($r->except('_token','_method','nama_tema','id_tema','nama_tema_baru'));
    if (count($r->nama_tema) > 0) {
      for ($i=0; $i < count($r->nama_tema) ; $i++) { 
        Tema::find($r['id_tema'][$i])
        ->update([
          'nama_tema'=>$r['nama_tema'][$i]
        ]);
      }
    }

    if ($r->has('nama_tema_baru')) {
      for ($i=0; $i < count($r->nama_tema_baru) ; $i++) { 
        Tema::create([
          'bidang_id'=>$id,
          'nama_tema'=>$r['nama_tema_baru'][$i]
        ]);
      }
    }
    $bidang=Bidang::find($id);
    Helpers::alert('success','Berhasil edit bidang '.$bidang->nama_bidang);
    return back();
   }
   public function destroy($id)
   {
    
    $bidang=Bidang::find($id);
    Helpers::alert('success','Berhasil hapus bidang '.$bidang->nama_bidang);

    $bidang->delete();
    return back();
   }
    public function hapusTema($id)
   {
    Tema::find($id)->delete();
    Helpers::alert('success','Berhasil hapus tema');
    return back();
   }
}
