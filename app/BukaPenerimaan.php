<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BukaPenerimaan extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey='id_buka_penerimaan';
    protected $guarded=[];
    public $timestamps=false;
    protected $table='buka_penerimaan';


    public function usulan()
    {
    	return $this->hasMany('App\Usulan','buka_penerimaan_id','id_buka_penerimaan');
    }

    public function jenis_usulan()
    {
    	return $this->belongsTo('App\JenisUsulan','jenis_usulan_id','id_jenis_usulan');
    }
    public function sumber_dana()
    {
    	return $this->belongsTo('App\SumberDana','sumber_dana_id','id_sumber_dana');
    }
    public function unit_kerja()
    {
    	return $this->belongsTo('App\UnitKerja','unit_kerja_id','id_unit_kerja');
    }
    public function skim()
    {
    	return $this->belongsTo('App\Skim','skim_id','id_skim');
    }
     public function tahun_anggaran()
    {
    	return $this->belongsTo('App\TahunAnggaran','tahun_anggaran_id','id_tahun_anggaran');
    }



    

    

}
