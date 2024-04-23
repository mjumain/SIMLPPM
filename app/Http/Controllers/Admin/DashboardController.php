<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use Uang;
use DB;
use DateTime;
use App\User;
use Tanggal;
use App\Pegawai;
use Helpers;
use App\BukaPenerimaan;
use App\Pelaksana;
use App\Usulan;
use App\TahunAnggaran;

class DashboardController extends Controller
{
  function __construct()
  {
    $this->middleware('permission:read-dashboard-admin')->only('index');

    $this->middleware('permission:read-dashboard-dosen')->only('indexDosen');
  }
  public function index(Request $request)
  {
    $ta = Helpers::tahun_anggaran_aktif();
    // dd($ta);

    $usulan = Usulan::whereIn('status', ['sedang_diajukan'])->whereHas('buka_penerimaan', function ($q) use ($ta) {
      $q->where('tahun_anggaran_id', $ta->id_tahun_anggaran);
    })->count();
    $usulan_direvisi = Usulan::whereIn('status', ['revisi'])->whereHas('buka_penerimaan', function ($q) use ($ta) {
      $q->where('tahun_anggaran_id', $ta->id_tahun_anggaran);
    })->count();
    $usulan_diterima = Usulan::whereIn('status', ['diterima'])->whereHas('buka_penerimaan', function ($q) use ($ta) {
      $q->where('tahun_anggaran_id', $ta->id_tahun_anggaran);
    })->count();
    $usulan_ditolak = Usulan::whereIn('status', ['ditolak'])->whereHas('buka_penerimaan', function ($q) use ($ta) {
      $q->where('tahun_anggaran_id', $ta->id_tahun_anggaran);
    })->count();

    $ta_grapik = TahunAnggaran::orderBy('tahun', 'asc')->where('tahun', '>=', '2017')->get();
    // dd($ta_grapik);
    $labeltahun = array();
    $jumlahpenelitianditerima = [];
    foreach ($ta_grapik as $ind => $t) {
      $labeltahun[] = $t->tahun;
      $jumlahpenelitianditerima[] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran)
            ->where('jenis_usulan_id', 1);
        })->count();
      // dd($ta->id_tahun_anggaran);
      
      $jumlahpenelitianditolak[] = Usulan::where('status', 'ditolak')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran)
            ->where('jenis_usulan_id', 1);
        })->count();

      $jumlahpengabdianditerima[] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran)
            ->where('jenis_usulan_id', 2);
        })->count();

      $jumlahpengabdianditolak[] = Usulan::where('status', 'ditolak')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran)
            ->where('jenis_usulan_id', 2);
        })->count();

      $upload['sudah'][] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran);
        })
        ->whereNotNull('file_laporan_kemajuan')
        ->count();

      $upload['sudah'][] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran);
        })
        ->whereNotNull('file_laporan_akhir')
        ->count();

      $upload['sudah'][] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran);
        })
        ->whereNotNull('file_artikel')
        ->count();

      $upload['sudah'][] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran);
        })
        ->whereNotNull('file_luaran')
        ->count();
      $upload['belum'][] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran);
        })
        ->whereNull('file_laporan_kemajuan')
        ->count();

      $upload['belum'][] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran);
        })
        ->whereNull('file_laporan_akhir')
        ->count();

      $upload['belum'][] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran);
        })
        ->whereNull('file_artikel')
        ->count();

      $upload['belum'][] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran);
        })
        ->whereNull('file_luaran')
        ->count();
      $menyerahkan['sudah'][] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran);
        })
        ->where('hard_proposal', 1)
        ->count();

      $menyerahkan['sudah'][] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran);
        })
        ->where('hard_laporan_kemajuan', 1)
        ->count();
      $menyerahkan['sudah'][] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran);
        })
        ->where('hard_laporan_akhir', 1)
        ->count();
      $menyerahkan['sudah'][] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran);
        })
        ->where('hard_artikel', 1)
        ->count();
      $menyerahkan['sudah'][] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran);
        })
        ->where('hard_luaran', 1)
        ->count();
      $menyerahkan['belum'][] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran);
        })
        ->where('hard_proposal', 0)
        ->count();

      $menyerahkan['belum'][] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran);
        })
        ->where('hard_laporan_kemajuan', 0)
        ->count();
      $menyerahkan['belum'][] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran);
        })
        ->where('hard_laporan_akhir', 0)
        ->count();
      $menyerahkan['belum'][] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran);
        })
        ->where('hard_artikel', 0)
        ->count();
      $menyerahkan['belum'][] = Usulan::where('status', 'diterima')
        ->whereHas('buka_penerimaan', function ($q) use ($t) {
          $q->where('tahun_anggaran_id', $t->id_tahun_anggaran);
        })
        ->where('hard_luaran', 0)
        ->count();
    }

    return view('admin.dashboard.dashboard-page', compact(
      'usulan',
      'usulan_direvisi',
      'usulan_diterima',
      'usulan_ditolak',
      'labeltahun',
      'jumlahpenelitianditerima',
      'jumlahpenelitianditolak',
      'jumlahpengabdianditerima',
      'jumlahpengabdianditolak',
      'upload',
      'menyerahkan'
    ));
  }
  public function indexDosen(Request $request)
  {
    $ta = Helpers::tahun_anggaran_aktif();
    $tawaran = Pelaksana::where('id_peg', auth()->user()->id_peg)
      ->where('jabatan', 'anggota')
      ->where('konfirmasi', 'menunggu')
      ->whereHas('usulan', function ($q) use ($ta) {
        $q->whereHas('buka_penerimaan', function ($r) use ($ta) {
          $r->where('tahun_anggaran_id', $ta->id_tahun_anggaran);
        });
      })
      ->count();
    $usulan_diajukan = Usulan::whereIn('status', ['sedang_diajukan', 'revisi'])->whereHas('pelaksana', function ($q) {
      $q->where('id_peg', auth()->user()->id_peg)
        ->where('konfirmasi', 'bersedia');
    })->whereHas('buka_penerimaan', function ($q) use ($ta) {
      $q->where('tahun_anggaran_id', $ta->id_tahun_anggaran);
    })->count();
    $usulan_revisi = Usulan::whereIn('status', ['revisi'])->whereHas('pelaksana', function ($q) {
      $q->where('id_peg', auth()->user()->id_peg)
        ->where('konfirmasi', 'bersedia');
    })->whereHas('buka_penerimaan', function ($q) use ($ta) {
      $q->where('tahun_anggaran_id', $ta->id_tahun_anggaran);
    })->count();

    $usulan_disetujui = Usulan::whereIn('status', ['diterima', 'revisi2'])->whereHas('pelaksana', function ($q) {
      $q->where('id_peg', auth()->user()->id_peg)
        ->where('konfirmasi', 'bersedia');
    })->whereHas('buka_penerimaan', function ($q) use ($ta) {
      $q->where('tahun_anggaran_id', $ta->id_tahun_anggaran);
    })->count();
    $usulan_ditolak = Usulan::whereIn('status', ['ditolak'])->whereHas('pelaksana', function ($q) {
      $q->where('id_peg', auth()->user()->id_peg)
        ->where('konfirmasi', 'bersedia');
    })->whereHas('buka_penerimaan', function ($q) use ($ta) {
      $q->where('tahun_anggaran_id', $ta->id_tahun_anggaran);
    })->count();

    $buka_penerimaan = BukaPenerimaan::where('tahun_anggaran_id', Helpers::tahun_anggaran_aktif()->id_tahun_anggaran)->get();
    return view('admin.dashboard.dashboard-dosen-page', compact('tawaran', 'usulan_diajukan', 'usulan_disetujui', 'usulan_ditolak', 'buka_penerimaan', 'usulan_revisi'));
  }
}
