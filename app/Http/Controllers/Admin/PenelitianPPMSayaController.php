<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Usulan;
use App\JenisUsulan;
use App\Bidang;
use App\BukaPenerimaan;
use Helpers;
use App\Pelaksana;
use File;
use Validator;
class PenelitianPPMSayaController extends Controller
{
  function __construct()
  {  
    $this->middleware('permission:read-penelitian-ppm-saya')->only('index');
    $this->middleware('permission:upload-laporan')->only('laporan','postLaporan','destroyLaporan');
    
    
  }
   public function index()
   {
   	  
      $usulan=Usulan::where('status','diterima')->whereHas('pelaksana',function($q){
        $q->where('id_peg',auth()->user()->id_peg)->where('konfirmasi','bersedia');

      })
      ->orderBy('tanggal_mulai','desc')
      ->get();
      return view('admin.penelitian-ppm-saya.penelitian-ppm-saya-page',compact('usulan'));
   }
   public function detail($id)
   {
    $data=Usulan::find(decrypt($id));
    return view('admin.penelitian-ppm-saya.detail-usulan-page',compact('data'));
   }
   public function laporan($id)
   {

    $data=Usulan::find(decrypt($id));
    return view('admin.penelitian-ppm-saya.laporan-page',compact('data'));
   }
   public function postLaporan(Request $request,$jenis,$id)
   {
      if ($jenis=="laporan-akhir"||$jenis=="laporan-akhir-turnitin") {
        $validasi=Validator::make($request->all(),['file_laporan'=>'mimes:pdf,PDF|max:11000',],['mimes'=>'Format file harus pdf!',
        'max'=>'Ukuran file terlalu besar!, maksimal ukuran 10 MB']);
      }else {
         $validasi=Validator::make($request->all(),['file_laporan'=>'mimes:pdf,PDF|max:5096',],['mimes'=>'Format file harus pdf!',
        'max'=>'Ukuran file terlalu besar!, maksimal ukuran 5 MB']);
         
      }
      if ($validasi->fails()) {
        return back()->withInput()->withErrors($validasi);
      }else{

        $file=$request->file('file_laporan');
        $usulan=Usulan::find(decrypt($id));
        if ($jenis=='proposal-turnitin') {
          File::delete(public_path('/dokumen/proposal/'.$usulan->file_proposal_turnitin));
          $input['file_proposal_turnitin']="proposal-turnitin".date('YmdHis').rand(100,999).".".strtolower($file->getClientOriginalExtension());
          $input['tgl_upload_proposal_turnitin']=date('Y-m-d H:i:s');
          $pindah=$file->move(public_path()."/dokumen/proposal",$input['file_proposal_turnitin']);
          $pesan="Berhasil Unggah Hasil Uji Turnitin Proposal";

        }
        if ($jenis=='laporan-kemajuan') {
          File::delete(public_path('/dokumen/laporan-kemajuan/'.$usulan->file_laporan_kemajuan));
          $input['file_laporan_kemajuan']="laporan-kemajuan-".date('YmdHis').rand(100,999).".".strtolower($file->getClientOriginalExtension());
          $input['tgl_upload_laporan_kemajuan']=date('Y-m-d H:i:s');
          $pindah=$file->move(public_path()."/dokumen/laporan-kemajuan",$input['file_laporan_kemajuan']);
          $pesan="Berhasil Unggah Laporan kemajuan";

        }
        if ($jenis=='laporan-akhir') {
          File::delete(public_path('/dokumen/laporan-akhir/'.$usulan->file_laporan_akhir));
          $input['file_laporan_akhir']="laporan-akhir-".date('YmdHis').rand(100,999).".".strtolower($file->getClientOriginalExtension());
          $input['tgl_upload_laporan_akhir']=date('Y-m-d H:i:s');
          $pindah=$file->move(public_path()."/dokumen/laporan-akhir",$input['file_laporan_akhir']);
          $pesan="Berhasil Unggah Laporan Akhir";
        }
        if ($jenis=='laporan-akhir-turnitin') {
          File::delete(public_path('/dokumen/laporan-akhir/'.$usulan->file_laporan_akhir_turnitin));
          $input['file_laporan_akhir_turnitin']="laporan-akhir-turnitin".date('YmdHis').rand(100,999).".".strtolower($file->getClientOriginalExtension());
          $input['tgl_upload_laporan_akhir_turnitin']=date('Y-m-d H:i:s');
          $pindah=$file->move(public_path()."/dokumen/laporan-akhir",$input['file_laporan_akhir_turnitin']);
          $pesan="Berhasil Unggah Hasil Uji Turnitin Laporan Akhir";
        }
        if ($jenis=='artikel') {
          File::delete(public_path('/dokumen/artikel/'.$usulan->file_artikel));
          $input['file_artikel']="artikel".date('YmdHis').rand(100,999).".".strtolower($file->getClientOriginalExtension());
          $input['tgl_upload_artikel']=date('Y-m-d H:i:s');
          $pindah=$file->move(public_path()."/dokumen/artikel",$input['file_artikel']);
          $pesan="Berhasil Unggah Artikel";
        }
        if ($jenis=='luaran') {
          File::delete(public_path('/dokumen/luaran/'.$usulan->file_artikel));
          $input['file_luaran']="luaran".date('YmdHis').rand(100,999).".".strtolower($file->getClientOriginalExtension());
          $input['tgl_upload_luaran']=date('Y-m-d H:i:s');
          $pindah=$file->move(public_path()."/dokumen/luaran",$input['file_luaran']);
          $pesan="Berhasil Unggah Luaran";
        }
        if ($jenis=='link-luaran') {
          $input['link_jurnal']=$request->link_jurnal;
          $pesan="Berhasil Input Link Luaran";
        }
        Helpers::log('Mengisi/mengunggah  '.str_replace('-','' ,$jenis).' pada usulan '.$usulan->judul);
        Usulan::find(decrypt($id))->update($input);
        Helpers::alert('success',$pesan);
        return back();

      }
   }
   public function destroyLaporan($jenis,$id)
   {
      

        
        $usulan=Usulan::find(decrypt($id));
        if ($jenis=='proposal-turnitin') {
          File::delete(public_path('/dokumen/proposal/'.$usulan->file_proposal_turnitin));
          $input['file_proposal_turnitin']=null;
          
          $pesan="Berhasil Hapus File Proposal Turnitin";
        }
        if ($jenis=='laporan-kemajuan') {
          File::delete(public_path('/dokumen/laporan-kemajuan/'.$usulan->file_laporan_kemajuan));
          $input['file_laporan_kemajuan']=null;
          $input['tgl_upload_laporan_kemajuan']=null;
          $pesan="Berhasil Hapus Laporan kemajuan";
        }
        if ($jenis=='laporan-akhir') {
          File::delete(public_path('/dokumen/laporan-akhir/'.$usulan->file_laporan_akhir));
          $input['file_laporan_akhir']=null;
          $input['tgl_upload_laporan_akhir']=null;
          $pesan="Berhasil Hapus Laporan Akhir";
        }
        if ($jenis=='laporan-akhir-turnitin') {
          File::delete(public_path('/dokumen/laporan-akhir/'.$usulan->file_laporan_akhir_turnitin));
          $input['file_laporan_akhir_turnitin']=null;
          $input['tgl_upload_laporan_akhir_turnitin']=null;
          $pesan="Berhasil Hapus Hasil Uji Turnitin Laporan Akhir";
        }
        if ($jenis=='artikel') {
          File::delete(public_path('/dokumen/artikel/'.$usulan->file_artikel));
          $input['file_artikel']=null;
          $input['tgl_upload_artikel']=null;
          
          $pesan="Berhasil Hapus Artikel";
        }
        if ($jenis=='luaran') {
          File::delete(public_path('/dokumen/luaran/'.$usulan->file_artikel));
          $input['file_luaran']=null;
          $input['tgl_upload_luaran']=null;
          $pesan="Berhasil Hapus Luaran";
        }
        if ($jenis=='link-luaran') {
          
          $input['link_jurnal']=null;
          
          $pesan="Berhasil Hapus Link Luaran";
        }
        Helpers::log('Menghapus upload  '.str_replace('-','' ,$jenis).' pada usulan '.$usulan->judul);
        Usulan::find(decrypt($id))->update($input);
        Helpers::alert('success',$pesan);
        return back();

      
     

    
   }
   
}
