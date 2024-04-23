<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TahunAnggaran extends Model
{
	protected $connection = 'mysql';
    protected $primaryKey='id_tahun_anggaran';
    protected $guarded=[];
    public $timestamps=false;
    protected $table='tahun_anggaran';

    public function buka_penerimaan()
    {
    	return $this->hasMany('App\BukaPenerimaan','tahun_anggaran_id','id_tahun_anggaran');
    }

   
    

}
