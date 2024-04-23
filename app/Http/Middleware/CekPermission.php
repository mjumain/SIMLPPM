<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Permission;
use DB;
class CekPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$permission)
    {

        $permission=Permission::where('permission',$permission)->first();
        $idroles=array();
        $roles=Auth::user()->roles;
        foreach ($roles as $role) {
            $idroles[]=$role->id_role;
        }
        

        $permissionid=$permission->id_permission;
        $cek=DB::table('roles_has_permissions')->whereIn('role_id',$idroles)->where('permission_id',$permissionid)->count();
        if ($cek < 1) {
            if ($request->ajax()) {
                return 'Unauthorized';
            }
            else
            {
                $permission=Permission::where('id_permission',$permissionid)->first();
                $pesan="Ooops, Anda tidak punya hak akses untuk melakukan tindakan <b>".$permission->keterangan_permission."</b> hubungi admin yang berwenang untuk mendapatkan akses ke tindakan yang dimaksud";
                return redirect('/unauthorized')->with('noakses',$pesan);
            }
        }


        return $next($request);
    }
}
