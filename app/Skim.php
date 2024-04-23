<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skim extends Model
{
	protected $connection = 'mysql';
    protected $primaryKey='id_skim';
    protected $guarded=[];
    public $timestamps=false;
    protected $hidden=[];
    protected $table='skim';    

}
