<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TahunAnggaran;
class JenisSkema extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey='id_jenis_skema';
    protected $guarded=[];
    public $timestamps=false;
    protected $table='jenis_skema';


    public function jenis_usulan()
    {
        return $this->belongsTo('App\JenisUsulan','jenis_usulan_id','id_jenis_usulan');
    }

    public function tkt()
    {
        return $this->belongsToMany('App\TKT','jenis_skema_has_tkt','jenis_skema_id','tkt_id');
    }

    public function luaran_wajib()
    {
        return $this->belongsToMany('App\Luaran','jenis_skema_has_luaran_wajib','jenis_skema_id','luaran_id');
    }
    public function luaran_tambahan()
    {
        return $this->belongsToMany('App\Luaran','jenis_skema_has_luaran_tambahan','jenis_skema_id','luaran_id');
    }

    public function borang_proposal()
      {
        $ta=TahunAnggaran::where('status',1)->first();
        return $this->hasMany('App\Borang','jenis_skema_id','id_jenis_skema')->where('tahap','proposal')->where('tahun_anggaran_id',$ta->id_tahun_anggaran);
      }
      public function borang_evaluasi_hasil()
      {
         $ta=TahunAnggaran::where('status',1)->first();
        return $this->hasMany('App\Borang','jenis_skema_id','id_jenis_skema')->where('tahap','evaluasi-hasil')->where('tahun_anggaran_id',$ta->id_tahun_anggaran);
      }
    
    

}
