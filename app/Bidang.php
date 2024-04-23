<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
	protected $connection = 'mysql';
    protected $primaryKey='id_bidang';
    protected $guarded=[];
    public $timestamps=false;
    protected $hidden=[];
    protected $table='bidang';

    public function tema()
    {
    	return $this->hasMany('App\Tema','bidang_id','id_bidang');
    }    

}
