<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataPendukung extends Model
{
	protected $connection = 'mysql';
    protected $primaryKey='id_data_pendukung';
    protected $guarded=[];
    public $timestamps=false;
    protected $table='data_pendukung';
    
    public function bank()
    {
        return $this->belongsTo('App\Bank','bank_id','id_bank');
    }
    

   

}
