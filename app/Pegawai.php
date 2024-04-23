<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TahunAnggaran;
class Pegawai extends Model
{
	
	// protected $connection = 'mysql';
	public $incrementing = false;
    protected $primaryKey='id_pegawai';
    protected $guarded=[];
    public $timestamps=false;
    protected $table='sql_simpeg_umjam.pegawai';



    public function user()
    {
    	return $this->belongsTo('App\User','id_pegawai','id_peg');
    }
    public function data_pendukung()
    {
    	return $this->hasOne('App\DataPendukung','pegawai_id','id_pegawai');
    }

    public function reviewer_proposal()
	  {
	    return $this->belongsToMany('App\Usulan','reviewer_usulan','reviewer_id','usulan_id')->withPivot('tahap','reviewer_ke')->wherePivot('tahap','proposal')
	      ->whereHas('buka_penerimaan',function($r){
	        $r->where('tahun_anggaran_id',TahunAnggaran::where('status',1)->first()->id_tahun_anggaran);

	      });

	  }
	  public function reviewer_evaluasi_hasil()
	  {
	     return $this->belongsToMany('App\Usulan','reviewer_usulan','reviewer_id','usulan_id')->withPivot('tahap','reviewer_ke')->wherePivot('tahap','evaluasi-hasil')
	      ->whereHas('buka_penerimaan',function($r){
	        $r->where('tahun_anggaran_id',TahunAnggaran::where('status',1)->first()->id_tahun_anggaran);

	      });

	  }

	  public function reviewer_tahun_anggaran()
	  {
	     return $this->belongsToMany('App\TahunAnggaran','reviewer_tahun_anggaran','pegawai_id','tahun_anggaran_id');

	  }

    
    
    

}
