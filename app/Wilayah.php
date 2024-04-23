<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
	protected $connection = 'mysql';
    protected $primaryKey='id_wilayah';
    protected $guarded=[];
    public $timestamps=false;
    protected $table='wilayah';
    public $incrementing = false;

   
    public function parent()
    {
      return $this->belongsTo('App\Wilayah','id_induk_wilayah','id_wilayah');
    }

    

    

}
