<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey='id_menu';
    protected $guarded=[];
    public $timestamps=false;
    protected $table='menus';

     public function submenus()
    { 
      return $this->hasMany('App\Menu','parent_id','id_menu');
    }
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'roles_has_menus','menu_id','role_id');
    }
    public function permissions()
    {
        return $this->hasMany('App\Permission', 'menu_id','id_menu');
    }
    public function parent()
    {
        return $this->belongsTo('App\Menu','parent_id','id_menu');
    }
    

}
