<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey='id_role';
    protected $guarded=[];
    public $timestamps=false;
    protected $table='roles';

    public function users()
    {
        return $this->belongsToMany('App\User', 'users_has_roles','role_id','user_id');
    }
     public function menus()
    {
        return $this->belongsToMany('App\Menu', 'roles_has_menus','role_id','menu_id');
    }
    public function permissions()
    {
        return $this->belongsToMany('App\Permission', 'roles_has_permissions','role_id','permission_id');
    }
}
