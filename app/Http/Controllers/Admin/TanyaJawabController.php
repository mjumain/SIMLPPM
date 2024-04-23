<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TanyaJawab;
use Helpers;
class TanyaJawabController extends Controller
{
  function __construct()
  {  
    $this->middleware('permission:read-tanya-jawab')->only('index','show');
    
    $this->middleware('permission:update-tanya-jawab')->only('edit','update');
    $this->middleware('permission:delete-tanya-jawab')->only('destroy');
    
  }
   public function index()
   {
   	  $datas=TanyaJawab::orderBy('tanggal_tanya','desc')->get();
      return view('admin.tanya-jawab.tanya-jawab-page',compact('datas'));
   }
   

   public function edit($id)
   {
    $data=TanyaJawab::where('id_tanya_jawab',$id)->first();
    return response()->json($data);
   }
   public function update(Request $r, $id)
   {
    TanyaJawab::where('id_tanya_jawab',$id)->update($r->except('_token','_method'));
    $pertanyaan=TanyaJawab::where('id_tanya_jawab',$id)->first();
    Helpers::log('menjawab pertanyaan '.$pertanyaan->nama_pelaku.' :'.$pertanyaan->pertanyaan.'.  tanggapan : '.$pertanyaan->tanggapan);
    Helpers::alert('success','Berhasil buat komentar');
    return back();
   }
   public function destroy($id)
   {
    $a=TanyaJawab::find($id);
    Helpers::log('menghapus pertanyaan '.$a->nama_pelaku.' :'.$a->pertanyaan);
    $a->delete();
    Helpers::alert('success','Berhasil hapus pertnayaan/tanggapan');
    return back();
   }
}
