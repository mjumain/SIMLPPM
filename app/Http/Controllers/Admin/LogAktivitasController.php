<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LogAktivitas;
use DataTables;

class LogAktivitasController extends Controller
{
    function __construct()
  {  
    $this->middleware('permission:read-log')->only('index','show');
    
    
    
  }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $log=LogAktivitas::orderBy('tanggal','desc')->select('log_aktivitas.*');
            if ($request->tgl_awal!=""&&$request->tgl_akhir!="") {
                $log=$log->whereBetween('tanggal',[$request->tgl_awal." 00:00:00",$request->tgl_akhir." 00:00:00"]);
            }

            return DataTables::of($log)->addIndexColumn()->make(true);
        }
        return view('admin.log.log-page');
    }

   
   
}
