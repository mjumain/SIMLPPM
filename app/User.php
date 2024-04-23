<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $connection = 'mysql';
    protected $primaryKey='id_user';
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','created_at','updated_at',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Role','users_has_roles','user_id','role_id');
    }
    public function pegawai()
    {
        return $this->belongsTo('App\Pegawai','id_peg','id_pegawai');
    }
    public function camaru()
    {
        return $this->belongsTo('App\Camaru','username','no_test');
    }
    public function prodi()
    {
        return $this->belongsToMany('App\Prodi','user_instansi','id_user','id_prodi');
    }
    public function fakultas()
    {
        return $this->belongsToMany('App\Fakultas','user_instansi','id_user','id_prodi');
    }
    public function hasRole($id_role)
    {
        $roles=[];
        foreach (auth()->user()->roles as $r) {
            $roles[]=$r->id_role;
        }

        if (in_array($id_role,$roles)) return true;
        else return false;
        
    }
    public function cek_akses($nama_akses)
    {
        $akses=[];
        foreach (auth()->user()->roles as $r) {
            foreach ($r->permissions as $v) {
                $akses[]=$v->permission;
            }
        }

        if (in_array($nama_akses,$akses)) return true;
        else return false;
        
    }
}
