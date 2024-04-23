<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ruangan;
use App\Gedung;
use App\Fakultas;
use App\LokasiKampus;
use Helpers;
use DataTables;
class RuanganController extends Controller
{
  function __construct()
  {  
    $this->middleware('permission:read-data-ruangan')->only('index','show');
    $this->middleware('permission:create-data-ruangan')->only('create','store');
    $this->middleware('permission:update-data-ruangan')->only('edit','update');
    $this->middleware('permission:delete-data-ruangan')->only('destroy');
    
  }
   public function index(Request $request)
   {
   	  
      $fakultas=Fakultas::where('status','1')->get();
      $lokasis=LokasiKampus::all();
      if ($request->ajax()) {
            $datas=Ruangan::join('gedung','ruangan.gedung_id','=','gedung.id_gedung')
                        ->join('fakultas','gedung.fakultas_id','=','fakultas.id_fakultas')
                        ->join('lokasi_kampus','gedung.lokasi_kampus_id','=','lokasi_kampus.id_lokasi_kampus')
                        ->select('ruangan.*','gedung.*','fakultas.*','lokasi_kampus.*');
            return DataTables::of($datas)
            ->addColumn('aksi',function($q){
                $html="<div class='dropdown'>
                            <button id='dLabel' class='btn btn-primary btn-sm' type='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                <i class='fa fa-angle-down'></i> Aksi
                            </button>
                            <ul class='dropdown-menu dropdown-menu-right' aria-labelledby='dLabel'>
                                <li><a onclick=\"edit('$q->id_ruangan')\"> Edit</a></li>
                                <li><a onclick=\"hapus('$q->id_ruangan','$q->nama_ruangan')\">Delete</a></li>
                                <form action='".url('admin/master-data-ruangan/'.$q->id_ruangan)."' method='post' id='".$q->id_ruangan."'>
                                    <input type='hidden' name='_method' value='DELETE'>
                                    <input type='hidden' name='_token' value='".csrf_token()."'>
                                </form>
                                
                            </ul>
                        </div>";

                return $html;
            })
           

            ->addIndexColumn()
            ->escapeColumns('action')->make(true);
        }

      return view('admin.ruangan.ruangan-page',compact('fakultas','lokasis'));
   }
   public function store(Request $r)
   {
    Ruangan::create($r->all());
    Helpers::alert('success','Berhasil tambah ruangan');
    return back();
   }

   public function edit($id)
   {
    $data=Ruangan::where('id_ruangan',$id)->first();
    $json['id_ruangan']=$data->id_ruangan;
    $json['nama_ruangan']=$data->nama_ruangan;
    $json['singkatan_ruangan']=$data->singkatan_ruangan;
    $json['kapasitas']=$data->kapasitas;
    $json['id_gedung']=$data->gedung->id_gedung;
    $json['gedung']=$data->gedung->nama_gedung." - ".$data->gedung->fakultas->sebutan." ".$data->gedung->fakultas->nama_fakultas." - ".$data->gedung->lokasi_kampus->nama_lokasi;
    return response()->json($json);
   }
   public function update(Request $r, $id)
   {
    Ruangan::where('id_ruangan',$id)->update($r->except('_token','_method'));
    Helpers::alert('success','Berhasil edit ruangan');
    return back();
   }
   public function destroy($id)
   {
    Ruangan::where('id_ruangan',$id)->delete();
    Helpers::alert('success','Berhasil hapus ruangan');
    return back();
   }
   public function loadGedung(Request $request)
   {
    $datas=Gedung::join('fakultas','gedung.fakultas_id','=','fakultas.id_fakultas')
                  ->join('lokasi_kampus','gedung.lokasi_kampus_id','=','lokasi_kampus.id_lokasi_kampus')
                  ->where('gedung.nama_gedung','like','%'.$request->search.'%')
                  ->orWhere('fakultas.nama_fakultas','like','%'.$request->search.'%')
                  ->orWhere('lokasi_kampus.nama_lokasi','like','%'.$request->search.'%')
                  ->take(50)->get();
        $json=[];
        if ($datas) {
          
          foreach ($datas as $data) {

             $json[] = ['id'=>$data->id_gedung, 'text'=>$data->nama_gedung." - ".$data->fakultas->sebutan." ".$data->fakultas->nama_fakultas." - ".$data->nama_lokasi ];
              
              
          }
        }
      return response()->json($json);
   }
   public function loadRuangan(Request $request)
   {
    $datas=Ruangan::join('gedung','ruangan.gedung_id','=','gedung.id_gedung')
                  ->join('fakultas','gedung.fakultas_id','=','fakultas.id_fakultas')
                  ->join('lokasi_kampus','gedung.lokasi_kampus_id','=','lokasi_kampus.id_lokasi_kampus')
                  ->where('ruangan.nama_ruangan','like','%'.$request->search.'%')
                  ->where('gedung.nama_gedung','like','%'.$request->search.'%')
                  ->orWhere('fakultas.nama_fakultas','like','%'.$request->search.'%')
                  ->orWhere('lokasi_kampus.nama_lokasi','like','%'.$request->search.'%')
                  ->take(50)->get();
        $json=[];
        if ($datas) {
          
          foreach ($datas as $data) {

             $json[] = ['id'=>$data->id_ruangan, 'text'=>$data->nama_ruangan." Kapasitas [ ".$data->kapasitas." ] - ".$data->nama_gedung." - ".$data->nama_lokasi ];
              
              
          }
        }
      return response()->json($json);
   }

}
