<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisUsulan extends Model
{
	protected $connection = 'mysql';
    protected $primaryKey='id_jenis_usulan';
    protected $guarded=[];
    public $timestamps=false;
    protected $table='jenis_usulan';


    public function jenis_skema()
    {
        return $this->hasMany('App\JenisSkema','jenis_usulan_id','id_jenis_usulan');
    }
    
    

}
