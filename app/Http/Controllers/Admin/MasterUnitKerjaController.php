<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UnitKerja;
use Helpers;
class MasterUnitKerjaController extends Controller
{
  function __construct()
  {  
    $this->middleware('permission:read-unit-kerja')->only('index','show');
    $this->middleware('permission:create-unit-kerja')->only('create','store');
    $this->middleware('permission:update-unit-kerja')->only('edit','update');
    $this->middleware('permission:delete-unit-kerja')->only('destroy');
    
  }
   public function index()
   {
   	  $datas=UnitKerja::all();
      return view('admin.master-data.unit-kerja.unit-kerja-page',compact('datas'));
   }
   public function store(Request $r)
   {
    UnitKerja::create($r->all());
    Helpers::alert('success','Berhasil tambah unit kerja');
    Helpers::log('Menambah unit kerja');
    return back();
   }

   public function edit($id)
   {
    $data=UnitKerja::where('id_unit_kerja',$id)->first();
    return response()->json($data);
   }
   public function update(Request $r, $id)
   {
    UnitKerja::where('id_unit_kerja',$id)->update($r->except('_token','_method'));
    Helpers::alert('success','Berhasil edit unit kerja');
    Helpers::log('Memperbaharui unit kerja');
    return back();
   }
   public function destroy($id)
   {
    UnitKerja::where('id_unit_kerja',$id)->delete();
    Helpers::alert('success','Berhasil hapus unit kerja');
    Helpers::log('Menghapus unit kerja');
    return back();
   }
}
