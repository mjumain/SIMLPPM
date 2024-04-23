<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class PelaksanaMahasiswa extends Model
{
  protected $connection = 'mysql';
    protected $primaryKey='id_pelaksana_mahasiswa';
    protected $guarded=[];
    public $timestamps=false;
    protected $hidden=[];
    protected $table='pelaksana_mahasiswa';

    public function prodi()
    {
    	return $this->belongsTo('App\Prodi','prodi_id','id_prodi');
    }
}