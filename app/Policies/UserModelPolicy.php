<?php

namespace App\Policies;
use DB;
use App\Permission;
use Helpers;
use Illuminate\Auth\Access\HandlesAuthorization;
class UserModelPolicy
{
    use HandlesAuthorization;
    private $roles=[];
    function __construct()
    {
        foreach (auth()->user()->roles as $role) {
            $this->roles[]=$role->id_role;
        }
    }
    public function view()
    {

       $permissionid=Permission::where('permission','read-user')->first()->id_permission;
       $check=DB::table('roles_has_permissions')->whereIn('role_id',$this->roles)->where('permission_id',$permissionid)->first();
        if ($check) {
            return true;
        }
        return false;
    }
}
