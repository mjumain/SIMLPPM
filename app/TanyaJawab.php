<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TanyaJawab extends Model
{
	protected $connection = 'mysql';
    protected $primaryKey='id_tanya_jawab';
    protected $guarded=[];
    public $timestamps=false;
    protected $table='tanya_jawab';

    
    

}
