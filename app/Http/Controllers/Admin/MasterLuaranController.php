<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Luaran;
use Helpers;
class MasterLuaranController extends Controller
{
  function __construct()
  {  
    $this->middleware('permission:read-luaran')->only('index','show');
    $this->middleware('permission:create-luaran')->only('create','store');
    $this->middleware('permission:update-luaran')->only('edit','update');
    $this->middleware('permission:delete-luaran')->only('destroy');
    
  }
   public function index()
   {
      $datas=Luaran::all();
      return view('admin.master-data.luaran.luaran-page',compact('datas'));
   }
   public function store(Request $r)
   {
    Luaran::create($r->all());
    Helpers::alert('success','Berhasil tambah luaran');
    return back();
   }

   public function edit($id)
   {
    $data=Luaran::where('id_luaran',$id)->first();
    return response()->json($data);
   }
   public function update(Request $r, $id)
   {
    Luaran::where('id_luaran',$id)->update($r->except('_token','_method'));
    Helpers::alert('success','Berhasil edit luaran');
    return back();
   }
   public function destroy($id)
   {
    Luaran::where('id_luaran',$id)->delete();
    Helpers::alert('success','Berhasil hapus luaran');
    return back();
   }
}
