<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Luaran extends Model
{
	protected $connection = 'mysql';
    protected $primaryKey='id_luaran';
    protected $guarded=[];
    public $timestamps=false;
    protected $table='luaran';
    
    

}
