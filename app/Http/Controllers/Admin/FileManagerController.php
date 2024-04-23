<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FileManager;
use Helpers;
use File;
class FileManagerController extends Controller
{
  function __construct()
  {  
    $this->middleware('permission:read-file-manager')->only('index','show');
    $this->middleware('permission:create-file-manager')->only('create','store','directUpload');
    $this->middleware('permission:update-file-manager')->only('edit','update');
    $this->middleware('permission:delete-file-manager')->only('destroy');
    
  }
   public function index()
   {
   	  $datas=FileManager::all();
      return view('admin.file-manager.file-manager-page',compact('datas'));
   }
   public function store(Request $r)
   {
    $input['deskripsi']=$r->deskripsi;
    if ($r->hasFile('file_manager')) {
        
        $file=$r->file('file_manager');
        $input['nama_file_original']=$file->getClientOriginalName();
        $input['nama_file']= md5(date('YmdHis'.rand(1000,9999))).".".$file->getClientOriginalExtension();
        $input['link']='/file-manager/'.$input['nama_file'];
        $pindah=$file->move(public_path()."/file-manager/",$input['nama_file']);

      }
        FileManager::create($input);
    Helpers::alert('success','Berhasil tambah file');
    return back();
   }
   public function directUpload(Request $r) {
    $input = [];
    if ($r->hasFile('file')) {
        $file=$r->file('file');
       
        $input['nama_file_original']=$file->getClientOriginalName();
        $input['nama_file']= md5(date('YmdHis'.rand(1000,9999))).".".$file->getClientOriginalExtension();
        $input['link']='file-manager/'.$input['nama_file'];
        $pindah=$file->move(public_path()."/file-manager/",$input['nama_file']);

      }
    $file = FileManager::create($input);
    $file_path = url($file->link);
    return json_encode(['location' => $file_path]);
   }

   public function edit($id)
   {
    $data=FileManager::where('id_file_manager',$id)->first();
    return response()->json($data);
   }
   public function update(Request $r, $id)
   {
    FileManager::where('id_file_manager',$id)->update($r->except('_token','_method'));
    Helpers::alert('success','Berhasil edit file');
    return back();
   }
   public function destroy($id)
   {
    FileManager::where('id_file_manager',$id)->delete();
    Helpers::alert('success','Berhasil hapus file');
    return back();
   }
}
