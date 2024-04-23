<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\KontenStatis;
use Helpers;
use File;
class KontenStatisController extends Controller
{
   public function index()
   {
   	  $data=KontenStatis::first();
      return view('admin.setting.setting-konten-statis-page',compact('data'));
   }
   
   public function update(Request $r, $id)
   {
    $input=$r->except('_token','_method','gambar_step_pendaftaran');
    $update=KontenStatis::find($id);
    if ($r->has('gambar_step_pendaftaran')) {
      $gambar=$r->gambar_step_pendaftaran;
      $input['gambar_step_pendaftaran']=$gambar->getClientOriginalName();
      $gambar->move(public_path().'/img/',$input['gambar_step_pendaftaran']);
      if (File::exists($image=public_path("img/{$update->gambar_step_pendaftaran}"))) { // unlink or remove previous image from folder
            File::delete($image);
      }
    }
    $update->update($input);

    
    Helpers::alert('success','Berhasil edit konten statis');
    return back();
   }
   
  
}
