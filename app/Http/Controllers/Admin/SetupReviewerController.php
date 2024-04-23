<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SetupReviewer;
use Helpers;
use App\TahunAnggaran;
class SetupReviewerController extends Controller
{
  function __construct()
  {  
    $this->middleware('permission:read-reviewer')->only('index','show');
    $this->middleware('permission:create-reviewer')->only('create','store');
    
    $this->middleware('permission:delete-reviewer')->only('destroy');
    
  }
   public function index()
   {
      $datas=SetupReviewer::all();
      return view('admin.setup-aplikasi.reviewer.reviewer-page',compact('datas'));
   }
   public function store(Request $r)
   {
    $input['pegawai_id']=$r->pegawai_id;
    $input['tahun_anggaran_id']=TahunAnggaran::where('status',1)->first()->id_tahun_anggaran;
    SetupReviewer::create($input);
    Helpers::alert('success','Berhasil tambah reviewer');
    return back();
   }

   
   public function destroy($id)
   {
    $rev=SetupReviewer::find($id);

      Helpers::alert('success','Berhasil hapus '.Helpers::nama_gelar($rev->pegawai).' dari daftar reviewer');
    $rev->delete();
    return back();
   }
}
