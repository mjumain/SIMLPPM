<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileManager extends Model
{
	protected $connection = 'mysql';
    protected $primaryKey='id_file_manager';
    protected $guarded=[];
    public $timestamps=false;
    protected $table='file_manager';

   

    

    
    
    

}
