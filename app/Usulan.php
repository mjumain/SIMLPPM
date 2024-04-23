<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Usulan extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey='id_usulan';
    protected $guarded=[];
    public $timestamps=false;
    protected $hidden=[];
    protected $table='usulan';

   public function buka_penerimaan()
  {
    return $this->belongsTo("App\BukaPenerimaan","buka_penerimaan_id","id_buka_penerimaan");
  }
  public function tema()
  {
    return $this->belongsTo("App\Tema","tema_id","id_tema");
  }
  public function ketua()
  {
    return $this->hasOne("App\Pelaksana","usulan_id","id_usulan")->where('jabatan','ketua');
  }
  public function anggota()
  {
    return $this->hasMany("App\Pelaksana","usulan_id","id_usulan")->where('jabatan','anggota');
  }
  public function pelaksana()
  {
    return $this->hasMany("App\Pelaksana","usulan_id","id_usulan");
  }
  public function pelaksana_mahasiswa()
  {
    return $this->hasMany("App\PelaksanaMahasiswa","usulan_id","id_usulan");
  }
  public function lokasi()
  {
    return $this->belongsTo("App\Wilayah","wilayah_id","id_wilayah");
  }
   public function jenis_skema()
  {
    return $this->belongsTo("App\JenisSkema","jenis_skema_id","id_jenis_skema");
  }
   public function tkt()
  {
    return $this->belongsTo("App\TKT","tkt_id","id_tkt");
  }

   public function luaran_wajib()
  {
    return $this->belongsToMany("App\Luaran",'usulan_has_luaran_wajib',"usulan_id","luaran_id");
  }
   public function luaran_tambahan()
  {
    return $this->belongsToMany("App\Luaran",'usulan_has_luaran_tambahan',"usulan_id","luaran_id");
  }
   public function reviewer_proposal()
  {
    return $this->belongsToMany("App\Pegawai","reviewer_usulan","usulan_id","reviewer_id")->withPivot("id_reviewer_usulan","reviewer_ke","tahap","status_review","komentar","rekomendasi","nilai",'waktu_review')->where('tahap','proposal')->orderBy('reviewer_ke');
  }

  public function reviewer_evaluasi_hasil()
  {
    return $this->belongsToMany("App\Pegawai","reviewer_usulan","usulan_id","reviewer_id")->withPivot("id_reviewer_usulan","reviewer_ke","tahap","status_review","komentar","rekomendasi","nilai",'waktu_review')->where('tahap','evaluasi-hasil')->orderBy('reviewer_ke');
  }

  public function reviewer1_proposal()
  {
    return $this->hasOne("App\ReviewerUsulan","usulan_id","id_usulan")
    ->where('tahap','proposal')->where('reviewer_ke',1);
  }
  public function reviewer2_proposal()
  {
    return $this->hasOne("App\ReviewerUsulan","usulan_id","id_usulan")
    ->where('tahap','proposal')->where('reviewer_ke',2);
  }
  public function reviewer1_evaluasi_hasil()
  {
    return $this->hasOne("App\ReviewerUsulan","usulan_id","id_usulan")
    ->where('tahap','evaluasi-hasil')->where('reviewer_ke',1);
  }
  public function reviewer2_evaluasi_hasil()
  {
    return $this->hasOne("App\ReviewerUsulan","usulan_id","id_usulan")
    ->where('tahap','evaluasi-hasil')->where('reviewer_ke',2);
  }
  public function borang_penilaian($id_usulan,$tahap,$id_reviewer)
  {
    return DB::table('usulan_has_borang as a')
            ->join('usulan as b','a.usulan_id','=','b.id_usulan')
            ->join('borang as c','a.borang_id','=','c.id_borang')
            ->join('skor_borang as d','a.skor_borang_id','=','d.id_skor_borang')
            ->where('a.reviewer_id',$id_reviewer)
            ->where('b.id_usulan',$id_usulan)
            ->where('c.tahap',$tahap)
            ->select('a.nilai','c.bobot','c.tahap','c.komponen_penilaian','d.skor','d.keterangan')
            ->get();
  }

}
