<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blokir extends Model
{
	protected $connection = 'mysql';
    protected $primaryKey='id_blokir';
    protected $guarded=[];
    public $timestamps=true;
    protected $table='blokir';

    public function pegawai()
    {
    	return $this->belongsTo('App\Pegawai','id_peg','id_pegawai');
    }

    

    

}
