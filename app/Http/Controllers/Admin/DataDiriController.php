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
use App\DataPendukung;
class DataDiriController extends Controller
{
   function __construct()
  {  
    //$this->middleware('permission:read-dashboard-admin')->only('index');
   
  }
   public function index()
   {

      $data=Pegawai::where('pegawai.id_pegawai',auth()->user()->pegawai->id_pegawai)
            
            ->first();
      
            
      return view('admin.data-diri.data-diri-page',compact('data'));
   }
   public function dataUmum($id)
   {

      $data=Pegawai::where('pegawai.id_pegawai',decrypt($id))
            
            ->first();
      
            
      return view('admin.data-diri.data-diri-page',compact('data'));
   }

   public function update(Request $r, $id)
   {
      

      $arrayData['no_rek']=$r->no_rek;
      $arrayData['nama_direkening']=$r->nama_direkening;
      $arrayData['bank_id']=$r->bank_id;
      $arrayData['no_npwp']=$r->no_npwp;

      if ($r->hasFile('scan_rekening')) {
        
        $file=$r->file('scan_rekening');
        $arrayData['scan_rekening']="rekening-".$id."-".date('YmdHis').".".$file->getClientOriginalExtension();
        $pindah=$file->move(public_path()."/dokumen/rekening",$arrayData['scan_rekening']);
      }
      if ($r->hasFile('scan_npwp')) {
        
        $file=$r->file('scan_npwp');
        $arrayData['scan_npwp']="npwp-".$id."-".date('YmdHis').".".$file->getClientOriginalExtension();
        $pindah=$file->move(public_path()."/dokumen/npwp",$arrayData['scan_npwp']);
      }


     $cek=DataPendukung::where('pegawai_id',$id)->first();
     if (!$cek) {
        $arrayData['pegawai_id']=$id;
       DataPendukung::create($arrayData);
     }else {
        $cek->update($arrayData);
     }
     Helpers::alert('success','Berhasil memperbaharui data');
     return back();
   }
   
   


}
