<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\JenisSkema;
use Helpers;
class MasterJenisSkemaController extends Controller
{
  function __construct()
  {  
    $this->middleware('permission:read-jenis-skema')->only('index','show');
    $this->middleware('permission:create-jenis-skema')->only('create','store');
    $this->middleware('permission:update-jenis-skema')->only('edit','update');
    $this->middleware('permission:delete-jenis-skema')->only('destroy');
    
  }
   public function index()
   {
      $datas=JenisSkema::all();
      return view('admin.master-data.jenis-skema.jenis-skema-page',compact('datas'));
   }
   public function store(Request $r)
   {
    $in=JenisSkema::create($r->except('id_tkt','id_luaran_wajib','id_luaran_tambahan'));
    $in->tkt()->sync($r->id_tkt);
    $in->luaran_wajib()->sync($r->id_luaran_wajib);
    $in->luaran_tambahan()->sync($r->id_luaran_tambahan);
    Helpers::alert('success','Berhasil tambah jenis skema');
    return back();
   }

   public function edit($id)
   {
    $data['jenis_skema']=JenisSkema::where('id_jenis_skema',$id)->first();
    $data['tkt']=[];
    $data['luaran_wajib']=[];
    $data['luaran_tambahan']=[];
    foreach ($data['jenis_skema']->tkt as $v) {
      $data['tkt'][]=$v->id_tkt;
    }

    foreach ($data['jenis_skema']->luaran_wajib as $v) {
      $data['luaran_wajib'][]=$v->id_luaran;
    }
    foreach ($data['jenis_skema']->luaran_tambahan as $v) {
      $data['luaran_tambahan'][]=$v->id_luaran;
    }


    return response()->json($data);
   }
   public function update(Request $r, $id)
   {
    $u=JenisSkema::find($id);
    $u->tkt()->sync($r->id_tkt);
    $u->luaran_wajib()->sync($r->id_luaran_wajib);
    $u->luaran_tambahan()->sync($r->id_luaran_tambahan);

    $u->update($r->except('_token','_method','id_tkt','id_luaran_wajib','id_luaran_tambahan'));
    
    Helpers::alert('success','Berhasil edit jenis skema');
    return back();
   }
   public function destroy($id)
   {
    JenisSkema::where('id_jenis_skema',$id)->delete();
    Helpers::alert('success','Berhasil hapus jenis skema');
    return back();
   }
}
