<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfoPendaftaran extends Model
{
	protected $connection = 'mysql';
    protected $primaryKey='id_info_pendaftaran';
    protected $guarded=[];
    public $timestamps=true;
    protected $table='info_pendaftaran';

    public function user()
    { 
      return $this->belongsTo('App\User','created_by_user_id','id_user');
    }

    

    
    
    

}
