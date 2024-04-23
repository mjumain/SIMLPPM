<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InfoPendaftaran;
use Helpers;
class InfoPendaftaranController extends Controller
{
  function __construct()
  {  
    $this->middleware('permission:read-info-pendaftaran')->only('index','show');
    $this->middleware('permission:create-info-pendaftaran')->only('create','store');
    $this->middleware('permission:update-info-pendaftaran')->only('edit','update');
    $this->middleware('permission:delete-info-pendaftaran')->only('destroy');
    
  }
   public function index()
   {
   	  $datas=InfoPendaftaran::orderBy('created_at','desc')->get();
      return view('admin.info-pendaftaran.info-pendaftaran-page',compact('datas'));
   }
   public function create()
   {
    return view('admin.info-pendaftaran.create-info-pendaftaran-page');
   }
   public function store(Request $r)
   {
    $input=$r->all();
    $input['created_by_user_id']=auth()->user()->id_user;
    
    InfoPendaftaran::create($input);
    Helpers::log("Membuat artikel dengan judul ".$r->judul);
    Helpers::alert('success','Berhasil tambah info pendaftaran');
    return redirect('info-pendaftaran');
   }

   public function edit($id)
   {
    $data=InfoPendaftaran::where('id_info_pendaftaran',$id)->first();
    return view('admin.info-pendaftaran.edit-info-pendaftaran-page',compact('data'));
   }
   public function update(Request $r, $id)
   {
    
    InfoPendaftaran::where('id_info_pendaftaran',$id)->update($r->except('_token','_method'));
    Helpers::alert('success','Berhasil edit info pendaftaran');
    Helpers::log("Mengupdate artikel dengan judul ".$r->judul);
    return redirect('info-pendaftaran');
   }
   public function destroy($id)
   {
    $r=InfoPendaftaran::find($id);
    Helpers::log("menghapus artikel dengan judul ".$r->judul);
    Helpers::alert('success','Berhasil hapus artikel '.$r->judul);
    $r->delete();
    return back();
   }
}
