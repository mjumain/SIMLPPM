<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Skim;
use Helpers;
class MasterSkimController extends Controller
{
  function __construct()
  {  
    $this->middleware('permission:read-skim')->only('index','show');
    $this->middleware('permission:create-skim')->only('create','store');
    $this->middleware('permission:update-skim')->only('edit','update');
    $this->middleware('permission:delete-skim')->only('destroy');
    
  }
   public function index()
   {
   	  $datas=Skim::all();
      return view('admin.master-data.skim.skim-page',compact('datas'));
   }
   public function store(Request $r)
   {
    Skim::create($r->all());
    Helpers::alert('success','Berhasil tambah skim');
    return back();
   }

   public function edit($id)
   {
    $data=Skim::where('id_skim',$id)->first();
    return response()->json($data);
   }
   public function update(Request $r, $id)
   {
    Skim::where('id_skim',$id)->update($r->except('_token','_method'));
    Helpers::alert('success','Berhasil edit skim');
    return back();
   }
   public function destroy($id)
   {
    Skim::where('id_skim',$id)->delete();
    Helpers::alert('success','Berhasil hapus skim');
    return back();
   }
}
