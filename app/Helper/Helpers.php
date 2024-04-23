<?php

use Carbon\Carbon;
use App\LogAktivitas;
use App\TahunAnggaran;
use App\SetupAplikasi;
use App\Usulan;

class Helpers
{

  public static function cek_jumlah_kegiatan($id_jenis_usulan, $id_peg, $add_anggota = null)
  {
    $setup = Helpers::setup();
    $ta = Helpers::tahun_anggaran_aktif();

    $usulan_ketua = Usulan::whereHas('pelaksana', function ($q) use ($id_peg) {
      $q->where('id_peg', $id_peg)
        ->where('konfirmasi', 'bersedia')
        ->where('jabatan', 'ketua');
    })->whereHas('buka_penerimaan', function ($q) use ($ta, $id_jenis_usulan) {
      $q->where('tahun_anggaran_id', $ta->id_tahun_anggaran)
        ->where('jenis_usulan_id', $id_jenis_usulan);
    })->count();
    $usulan_anggota = Usulan::whereHas('pelaksana', function ($q) use ($id_peg) {
      $q->where('id_peg', $id_peg)
        ->where('jabatan', 'anggota');
    })->whereHas('buka_penerimaan', function ($q) use ($ta, $id_jenis_usulan) {
      $q->where('tahun_anggaran_id', $ta->id_tahun_anggaran)
        ->where('jenis_usulan_id', $id_jenis_usulan);
    })->count();

    $hasil['izin'] = true;
    $hasil['pesan'] = "";
    if ($id_jenis_usulan == 1) {
      if ($usulan_anggota >= $setup->atau_max_jumlah_menjadi_anggota_penelitian) {
        $hasil['izin'] = false;
        $hasil['pesan'] = "Maaf anda tidak dapat mengusulkan penelitian karena telah melampaui batas ketentuan yakni telah menjadi anggota di " . $usulan_anggota . ' usulan kegiatan penelitian';
        return $hasil;
      } else {
        //dd($id_peg);
        if ($usulan_ketua >= $setup->max_jumlah_menjadi_ketua_penelitian) {

          if (isset($add_anggota)) {
            if ($usulan_anggota >= $setup->max_jumlah_menjadi_anggota_penelitian) {
              $hasil['izin'] = false;
              $hasil['pesan'] = "Maaf anda tidak dapat mengusulkan penelitian karena telah melampaui batas ketentuan yakni telah menjadi ketua di " . $usulan_ketua . ' usulan dan  anggota di ' . $usulan_anggota . ' usulan kegiatan penelitian';
            } else {
              $hasil['izin'] = true;
              $hasil['pesan'] = '';
              return $hasil;
            }
          } else {

            $hasil['izin'] = false;
            $hasil['pesan'] = "Maaf anda tidak dapat mengusulkan penelitian karena telah melampaui batas ketentuan yakni telah menjadi ketua di " . $usulan_ketua . ' usulan kegiatan penelitian';
          }

          return $hasil;
        } else if ($usulan_anggota + $usulan_ketua >= $setup->atau_max_jumlah_menjadi_anggota_penelitian + $setup->atau_max_jumlah_menjadi_ketua_penelitian) {
          $hasil['izin'] = false;
          $hasil['pesan'] = "Maaf anda tidak dapat mengusulkan penelitian karena telah melampaui batas ketentuan yakni telah menjadi ketua di " . $usulan_ketua . ' usulan kegiatan penelitian';
          return $hasil;
        }
      }
    } else if ($id_jenis_usulan == 2) {

      if ($usulan_anggota >= $setup->atau_max_jumlah_menjadi_anggota_pengabdian) {
        $hasil['izin'] = false;
        $hasil['pesan'] = "Maaf anda tidak dapat mengusulkan pengabdian karena telah melampaui batas ketentuan yakni telah menjadi anggota di " . $usulan_anggota . ' usulan kegiatan pengabdian';
        return $hasil;
      } else {
        if ($usulan_ketua >= $setup->max_jumlah_menjadi_ketua_pengabdian) {
          if (isset($add_anggota)) {
            if ($usulan_anggota >= $setup->max_jumlah_menjadi_anggota_pengabdian) {
              $hasil['izin'] = false;
              $hasil['pesan'] = "Maaf anda tidak dapat mengusulkan pengabdian karena telah melampaui batas ketentuan yakni telah menjadi ketua di " . $usulan_ketua . ' usulan dan  anggota di ' . $usulan_anggota . ' usulan kegiatan pengabdian';
            } else {
              $hasil['izin'] = true;
              $hasil['pesan'] = '';
              return $hasil;
            }
          } else {

            $hasil['izin'] = false;
            $hasil['pesan'] = "Maaf anda tidak dapat mengusulkan penelitian karena telah melampaui batas ketentuan yakni telah menjadi ketua di " . $usulan_ketua . ' usulan kegiatan pengabdian';
          }
        } else if ($usulan_anggota + $usulan_ketua >= $setup->atau_max_jumlah_menjadi_anggota_pengabdian + $setup->atau_max_jumlah_menjadi_ketua_pengabdian) {
          $hasil['izin'] = false;
          $hasil['pesan'] = "Maaf anda tidak dapat mengusulkan pengabdian karena telah melampau batas ketentuan yakni telah menjadi ketua di " . $usulan_ketua . ' usulan kegiatan pengabdian';
          return $hasil;
        }
      }
    }
    return $hasil;
  }

  public static function lokasi($usulan)
  {
    if ($usulan->lokasi) {

      return $usulan->nama_desa . ', ' . $usulan->lokasi->nama_wilayah . ', ' . $usulan->lokasi->parent->nama_wilayah . ', ' . $usulan->lokasi->parent->parent->nama_wilayah;
    } else {
      return "-";
    }
  }
  public static function jk($jk)
  {

    if ($jk == 'LK') {
      $jekel = "Laki-Laki";
    } else if ($jk == 'Ik') {
      $jekel = "Laki-Laki";
    } else if ($jk == 'lk') {
      $jekel = "Laki-Laki";
    } else if ($jk == 'pr') {

      $jekel = 'Perempuan';
    } else {
      $jekel = $jk;
    }
    return $jekel;
  }
  public static function do_something($usulan)
  {
    if ($usulan->status == 'belum' || $usulan->status == 'revisi') {
      if ($usulan->ketua->id_peg == auth()->user()->id_peg) {
        return true;
      }
    }
    return false;
  }
  public static function tahun_anggaran_aktif()
  {
    return TahunAnggaran::where('status', 1)->first();
  }
  public static  function jabatan($jab)
  {
    if ($jab == 'ketua') {
      $stat = "<span class='label label-success'>Ketua</span>";
    } elseif ($jab == 'anggota') {
      $stat = "<span class='label label-info'>Anggota</span>";
    }

    return $stat;
  }
  public static function status_review($stat)
  {
    if ($stat == 'belum') {
      $stat = "<span class='label label-info'>Belum direview</span>";
    } elseif ($stat == 'sudah') {
      $stat = "<span class='label label-success'>Sudah direview</span>";
    } elseif ($stat == 'proses') {
      $stat = "<span class='label label-primary'>Dalam Proses</span>";
    }

    return $stat;
  }
  public static  function konfirmasi($kon)
  {
    if ($kon->konfirmasi == 'menunggu') {
      $stat = "<span class='label label-primary'>Menuggu Konfirmasi</span>";
    } elseif ($kon->konfirmasi == 'bersedia') {
      $stat = "<span class='label label-success'>Bersedia</span>";
    } elseif ($kon->konfirmasi == 'menolak') {
      $stat = "<span class='label label-danger'>Menolak</span>";
    } elseif ($kon->konfirmasi == 'belum') {
      $stat = "<span class='label label-default'>Belum Isi</span>";
    }

    return $stat;
  }
  public static function setup()
  {
    return SetupAplikasi::first();
  }
  public static function alert($type = null, $message = null)
  {
    $alert = ['type' => $type, 'message' => $message];
    Session::flash('alert', $alert);
  }
  public static function nama_gelar($data)
  {
    if (!$data->user) {
      $nama_gelar = $data->nama_lengkap;
    } else {
      if (!empty($data->user->gelar_belakang)) $koma = ',';
      else $koma = '';
      $nama_gelar = $data->user->gelar_depan . ' ' . $data->nama_lengkap . $koma . ' ' . $data->user->gelar_belakang;
    }

    //$nama_gelar=$data->nama;
    return $nama_gelar;
  }

  public static function label_aktif($a)
  {
    $status = "";
    if ($a == '1') {
      $status = "<span class='label label-success'>Aktif</span>";
    } else {
      $status = "<span class='label label-danger'>Tidak Aktif</span>";
    }

    return $status;
  }
  public static function status_usulan($a)
  {
    $status = "";
    if ($a == 'belum') {
      $status = "<span class='label label-default'>Belum Diajukan</span>";
    } elseif ($a == 'sedang_diajukan') {
      $status = "<span class='label label-primary'>Sedang Diajukan</span>";
    } elseif ($a == 'diterima') {
      $status = "<span class='label label-success'>Diterima</span>";
    } elseif ($a == 'ditolak') {
      $status = "<span class='label label-danger'>Ditolak</span>";
    } elseif ($a == 'revisi') {
      $status = "<span class='label label-warning'>Revisi</span>";
    } elseif ($a == 'revisi2') {
      $status = "<span class='label label-warning'>Revisi ke-2</span>";
    } else {
      $status = "<span class='label label-danger'>Unknow</span>";
    }

    return $status;
  }
  public static function status_penerimaan($data)
  {
    $status = "";
    //dd($data);
    if ($data->jadwal_buka > date('Y-m-d')) {
      $status = "<span class='label label-primary'>Belum Buka</span>";
    } elseif ($data->jadwal_buka <= date('Y-m-d') && $data->jadwal_tutup >= date('Y-m-d')) {
      $status = "<span class='label label-success'>Buka</span>";
    } else if ($data->jadwal_tutup < date('Y-m-d')) {
      $status = "<span class='label label-danger'>Tutup</span>";
    } else {
      $status = "unknow";
    }
    return $status;
  }


  public static function log($aktivitas, $nama_pelaku = null)
  {

    if (Session::has('userlogin')) {
      $datauseraktif = Session::get('userlogin');
      if ($datauseraktif['id_pelaku'] != 'support') {
        $inserlog = new LogAktivitas();
        $inserlog->ip = $datauseraktif['ip'];
        $inserlog->id_pelaku = $datauseraktif['id_pelaku'];
        $inserlog->nama_pelaku = $datauseraktif['nama_pelaku'];
        $inserlog->user_agent = $datauseraktif['user_agent'];
        $inserlog->tanggal = date('Y-m-d H:i:s');
        $inserlog->aktifitas = $aktivitas;
        $inserlog->save();
      }
    } else {


      ($nama_pelaku == null) ? $nama = "visitor" : $nama = $nama_pelaku;

      $inserlog = new LogAktivitas();
      $inserlog->ip = request()->ip();
      $inserlog->id_pelaku = 'visitor';
      $inserlog->nama_pelaku = $nama;
      $inserlog->user_agent = request()->server('HTTP_USER_AGENT');
      $inserlog->tanggal = date('Y-m-d H:i:s');
      $inserlog->aktifitas = $aktivitas;
      $inserlog->save();
    }
  }
  public static function cek_data($data)
  {
    if (isset($data)) return $data;
    else return '-';
  }
  public static function cek_file($data)
  {
    if (isset($data)) return 'Ada';
    else return 'Tidak Ada';
  }

  public static function clean($validasi)
  {
    return ucwords(str_replace('_', ' ', $validasi));
  }


  public static function clean_string($string)
  {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
  }
  public static function cek_dokumen($masukan, $jenis)
  {
    if ($masukan != '') {
      $stat = "<a href='" . url('/dokumen/' . $jenis . '/' . $masukan) . "' target='_blank'  class='btn btn-sm btn-info'><i class='fa fa-search'></i> Lihat</a> ";
    } else {
      $stat = "<span class='label label-danger'>Belum Upload</span>";
    }
    return $stat;
  }
  public static function cek_penyerahan($masukan)
  {
    if ($masukan == '0') {
      $stat = "<span class='label label-danger'>Belum Menyerahkan</span>";
    } elseif ($masukan == '1') {
      $stat = "<span class='label label-success'>Sudah Menyerahkan</span>";
    } else {
      $stat = "<span class='label label-primary'>Tidak Perlu</span>";
    }

    return $stat;
  }
  public static function online()
  {

    $timestampsekarangminus5menit = Carbon::now()->subMinutes(10)->toDateTimeString();
    $online = LogAktivitas::orderBy('tanggal', 'desc')->where('tanggal', '>=', $timestampsekarangminus5menit)->groupBy('nama_pelaku')->get();
    return "<span class='label label-info'>" . count($online) . " online</span>";
  }
}
