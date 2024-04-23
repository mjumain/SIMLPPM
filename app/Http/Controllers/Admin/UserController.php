<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use  DataTables;
use Tanggal;
use Helpers;
use App\Biodata;
use App\Role;
use DB;
use App\Pelaksana;
class UserController extends Controller
{   
  function __construct()
  {  
    $this->middleware('permission:read-user')->only('index','show');
    $this->middleware('permission:create-user')->only('create','store');
    $this->middleware('permission:update-user')->only('edit','update');
    $this->middleware('permission:delete-user')->only('destroy');
    
  }
  
  public function index(Request $request)
  {
    
    
    if ($request->ajax()) {
      $user=User::join('sql_simpeg_umjam.pegawai as a','a.id_pegawai','=','users.id_peg')
      ->select('users.*','a.*');
      return DataTables::of($user)
      ->addColumn('action',function($q){
        $html="<a class='btn btn-xs btn-info' href=\"".url('manage-user/'.$q->id_user.'/edit')."\"><i class='fa fa-edit'></i> Edit</a> ";
        $html.="| <a class='btn btn-xs btn-danger' href=\"".url('manage-user/'.$q->id_peg.'/hapus')."\" onclick=\"return confirm('Apakah yakin hapus user ".Helpers::nama_gelar($q->pegawai)."')\"><i class='fa fa-trash'></i> Hapus</a> ";

        return $html;
      })
      ->addColumn('created_at',function($q){
        return Tanggal::time_indo($q->created_at);
      })
      ->addColumn('nama_gelar',function($q){
        return Helpers::nama_gelar($q->pegawai);
      })
      
      ->addColumn('roles', function (User $user) {
        return $user->roles->map(function($roles) {
          return "- ".$roles->nama_role;
        })->implode('<br>');
      })
      

      ->addIndexColumn()
      ->escapeColumns('action','roles')->make(true);
    }
    return view('admin.users.users-page');
  }

  
  public function create()
  {
    $roles=Role::all();
    return view('admin.users.create-users-page',compact('roles'));
  }

  public function store(Request $request)
  {
    $cek=User::where('id_peg',$request->id_peg)->first();
    if ($cek) {
      Helpers::alert('danger','Gagal tambah akun, karena sudah ada');
    
    
      return redirect('manage-user');
    }
    $u=User::create([
      'id_peg'=>$request->id_peg,
      'username'=>$request->username,
      'password'=>bcrypt($request->password),
      'jenis_akun'=>$request->jenis_akun,
      'status_akun'=>'aktif'
    ]);
    $u->roles()->sync($request->role_id);

    Helpers::alert('success','Akun baru berhasil ditambahakan');
    
    
    return redirect('manage-user');
  }
  public function show($id)
  {
        //
  }

  public function edit($id)
  {
    $user=User::findOrFail($id);
    $user_roles=[];
    $roles=Role::all();
    $user_roles=[];
    
    
    foreach ($user->roles as $role) {
      $user_roles[]=$role->id_role;
    }
    
    return view('admin.users.edit-users-page',compact('user_roles','user','roles'));
  }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $user=User::where('id_user',$id);
      $password="";

      
       User::find($id)->update([
          'jenis_akun'=>$request->jenis_akun,
          'username'=>$request->username
        ]);
      $rolesupdate=$user->first()->roles()->sync($request->roles);
      if (isset($request->password)) {
        User::find($id)->update([
          'password'=>bcrypt($request->password),
        ]);

        $password=" dan password baru user adalah ".$request->password;
      }
      
      Helpers::alert('success','Data pengguna berhasil diperbaharui '.$password);
      return back();

    }
    public function destroy($id)
    {
      $cek=Pelaksana::where('id_peg',$id)->count();
      if ($cek > 0) {
        Helpers::alert('danger','Tidak bisa hapus user karena sudah ada sebagai anggota penelitian/ppm, solusinya jika ada perubahan NIDN sebgai username maka pilih edit');
      }else{
        User::where('id_peg',$id)->delete();
        Helpers::alert('success','Berhasil hapus data!');
      }

      return back();

    }

    
    
    
  }
