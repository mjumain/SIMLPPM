<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
	protected $connection = 'mysql';
    protected $primaryKey='id_permission';
    protected $guarded=[];
    public $timestamps=false;
    protected $table='permissions';

    
    public function menu()
    {
        return $this->belongsTo('App\Menu', 'menu_id','id_menu');
    }
    

}
