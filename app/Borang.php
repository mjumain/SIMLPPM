<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Borang extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey='id_borang';
    protected $guarded=[];
    public $timestamps=false;
    protected $table='borang';

    public function skor_borang()
    {
    	return $this->hasMany("App\SkorBorang",'borang_id','id_borang');
    }

    public function usulan()
    {
    	return $this->belongsToMany("App\Usulan",'usulan_has_borang','borang_id','usulan_id');	
    }


}
