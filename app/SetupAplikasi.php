<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetupAplikasi extends Model
{
	protected $connection = 'mysql';
    protected $primaryKey='id_setup_aplikasi';
    protected $guarded=[];
    public $timestamps=false;
    protected $hidden=[];
    protected $table='setup_aplikasi';    

}
