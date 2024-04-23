<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pegawai;
use App\InfoPendaftaran;

use Helpers;
use DB;

class HomeController extends Controller
{
  public function index()
  {
    $informasi = InfoPendaftaran::orderBy('created_at', 'desc')
      ->where('status_info', 'published')
      ->paginate(10);
    return view('front-page.home.informasi', compact('informasi'));
  }
  public function detailInfo($id)
  {
    $informasi = InfoPendaftaran::find(decrypt($id));

    return view('front-page.home.informasi-detail', compact('informasi'));
  }
  public function rekapData()
  {
    return view('front-page.home.rekap');
  }
  public function loadDosenPegawai(Request $request)
  {

    $datas = Pegawai::where(function ($q) use ($request) {
      $q->where('nip', 'like', '%' . $request->search . '%')
        ->orWhere('nama_lengkap', 'like', '%' . $request->search . '%');
    })->take(50)->get();

    $json = [];
    if ($datas) {

      foreach ($datas as $data) {

        $json[] = ['id' => $data->id_pegawai, 'text' => $data->nip . " - " . Helpers::nama_gelar($data)];
      }
    }

    return response()->json($json);
  }
}
