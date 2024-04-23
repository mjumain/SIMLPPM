<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TahunAnggaran;
use App\SetupAplikasi;
use Helpers;
use App\Bidang;
use App\Tema;
class SetupTahunPenerimaanCOntroller extends Controller
{
  function __construct()
  {  
    $this->middleware('permission:read-tahun-penerimaan')->only('index','show');
    $this->middleware('permission:create-tahun-penerimaan')->only('create','store');
    $this->middleware('permission:update-tahun-penerimaan')->only('edit','update');
    $this->middleware('permission:delete-tahun-penerimaan')->only('destroy');
    $this->middleware('permission:aktifkan-tahun-penerimaan')->only('aktifkan');
    $this->middleware('permission:update-setup')->only('ubahSetup');
    
  }
   public function index()
   {
      $datas=TahunAnggaran::orderBy('tahun','desc')->get();
      $setup=SetupAplikasi::first();
      return view('admin.setup-aplikasi.tahun-penerimaan.tahun-penerimaan-page',compact('datas','setup'));
   }
   public function store(Request $r)
   {
    $input=$r->all();
    $input['created_by_user']=auth()->user()->id_user;
    $insert=TahunAnggaran::create($input);
    Helpers::alert('success','Berhasil tambah tahun penerimaan');
    //akan membuat resentra baru untuk tahun yang dibuat berikutnya
        $resentralama=Bidang::where('tahun_anggaran_id','1')->get();
        foreach ($resentralama as $res) {
            $bidang=Bidang::create(['tahun_anggaran_id'=>$insert->id_tahun_anggaran,'nama_bidang'=>$res->nama_bidang,'jenis_usulan_id'=>$res->jenis_usulan_id]);
            //buat pula relasi ke tema
            foreach ($res->tema as $tem) {
                Tema::create(['bidang_id'=>$bidang->id_bidang,'nama_tema'=>$tem->nama_tema]);
            }

        }
    return back();
   }

   public function edit($id)
   {
    $data=TahunAnggaran::where('id_tahun_anggaran',$id)->first();
    return response()->json($data);
   }
   public function update(Request $r, $id)
   {
    TahunAnggaran::where('id_tahun_anggaran',$id)->update($r->except('_token','_method'));
    Helpers::alert('success','Berhasil edit tahun penerimaan');
    return back();
   }
   public function destroy($id)
   {
    $tahun=TahunAnggaran::find($id);
    if ($tahun->buka_penerimaan->count() > 0) {
      Helpers::alert('danger','Opps! tidak boleh dihapus karena data penelitan/pengabdian sudah ada');
      return back();
    }elseif ($tahun->status==1) {
      Helpers::alert('danger','Opps! tidak boleh dihapus karena merupakan setup aktif');
      return back();
    }

    $tahun->delete();
    Helpers::alert('success','Berhasil hapus tahun penerimaan');
    return back();
   }

  public function aktifkan($id)
   {
    TahunAnggaran::where('id_tahun_anggaran',$id)->update(['status'=>1]);
    TahunAnggaran::where('id_tahun_anggaran','!=',$id)->update(['status'=>0]);
    Helpers::alert('success','Berhasil mengaktifkan tahun penerimaan');
    return back();
   }

   public function ubahSetup(Request $r, $id)
   {
    SetupAplikasi::find($id)->update($r->except('_token','_method'));
    Helpers::alert('success','Berhasil edit setup aplikasi');
    return back();
   }
}
