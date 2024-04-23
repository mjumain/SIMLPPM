<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
	protected $connection = 'mysql';
    protected $primaryKey='id_log_aktivitas';
    protected $guarded=[];
    public $timestamps=false;
    public $incrementing = false;
    protected $table='log_aktivitas';

    
    

    
    
    

}
