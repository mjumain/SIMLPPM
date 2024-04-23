<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SumberDana;
use Helpers;
class MasterSumberDanaController extends Controller
{
  function __construct()
  {  
    $this->middleware('permission:read-sumber-dana')->only('index','show');
    $this->middleware('permission:create-sumber-dana')->only('create','store');
    $this->middleware('permission:update-sumber-dana')->only('edit','update');
    $this->middleware('permission:delete-sumber-dana')->only('destroy');
    
  }
   public function index()
   {
   	  $datas=SumberDana::all();
      return view('admin.master-data.sumber-dana.sumber-dana-page',compact('datas'));
   }
   public function store(Request $r)
   {
    SumberDana::create($r->all());
    Helpers::alert('success','Berhasil tambah sumber dana');
    return back();
   }

   public function edit($id)
   {
    $data=SumberDana::where('id_sumber_dana',$id)->first();
    return response()->json($data);
   }
   public function update(Request $r, $id)
   {
    SumberDana::where('id_sumber_dana',$id)->update($r->except('_token','_method'));
    Helpers::alert('success','Berhasil edit sumber dana');
    return back();
   }
   public function destroy($id)
   {
    SumberDana::where('id_sumber_dana',$id)->delete();
    Helpers::alert('success','Berhasil hapussumber dana');
    return back();
   }
}
