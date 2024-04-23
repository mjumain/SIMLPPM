<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Blokir;
use Helpers;
class BlokirController extends Controller
{
  function __construct()
  {  
    $this->middleware('permission:read-blokir')->only('index','show');
    $this->middleware('permission:create-blokir')->only('create','store');
    $this->middleware('permission:update-blokir')->only('edit','update');
    $this->middleware('permission:delete-blokir')->only('destroy');
    
  }
   public function index()
   {
      $datas=Blokir::orderBy('created_at','desc')->get();
      return view('admin.setup-aplikasi.blokir.blokir-page',compact('datas'));
   }
   public function store(Request $r)
   {
    $in=Blokir::create($r->except('_token'));
    Helpers::log("memblokir  ".Helpers::nama_gelar($in->pegawai));
    Helpers::alert('success','Berhasil tambah data blokir');
    return back();
   }

   public function edit($id)
   {
    
    $in=Blokir::find($id);
    $data['id_blokir']=$in->id_blokir;
    $data['id_peg']=$in->id_peg;
    $data['nip']=$in->pegawai->nip;
    $data['nama_peg']=Helpers::nama_gelar($in->pegawai);
    $data['alasan']=$in->alasan;
    $data['status_blokir']=$in->status_blokir;
    return response()->json($data);
   }
   public function update(Request $r, $id)
   {
    $u=Blokir::find($id);
    Helpers::alert('success','Berhasil edit blokir ',Helpers::nama_gelar($u->pegawai));
    Helpers::log("update blokir  ".Helpers::nama_gelar($u->pegawai));
    $u->update($r->except('_token','_method'));
    return back();
   }
   public function destroy($id)
   {
    $data=Blokir::find($id);
    Helpers::log("hapus blokir  ".Helpers::nama_gelar($data->pegawai));
    Helpers::alert('success','Berhasil hapus '.Helpers::nama_gelar($data->pegawai).'dari daftar blokir');
    $data->delete();
    return back();
   }
}
