<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pelaksana extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey='id_pelaksana';
    protected $guarded=[];
    public $timestamps=false;
    protected $table='pelaksana';


    public function pegawai()
    {
    	return $this->belongsTo('App\Pegawai','id_peg','id_pegawai');
    }
    public function usulan()
    {
    	return $this->belongsTo('App\Usulan','usulan_id','id_usulan');
    }

    
    

}
