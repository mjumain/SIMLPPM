<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use DataTables;
use Tanggal;
use Helpers;
use App\Role;
use App\Menu;
class RoleController extends Controller
{
   function __construct()
  {  
    $this->middleware('permission:read-role')->only('index','show');
    $this->middleware('permission:create-role')->only('create','store');
    $this->middleware('permission:update-role')->only('edit','update');
    $this->middleware('permission:delete-role')->only('destroy');
    
  }
  public function index()
  {
    $roles=Role::all();
    return view('admin.roles.roles-page',compact('roles'));
  }

  
  public function create()
  {
    $menus=Menu::where('parent_id',0)->get();
    return view('admin.roles.create-roles-page',compact('menus'));
  }

  public function store(Request $request)
  {
    $role=Role::create($request->only('nama_role','keterangan_role'));
    $role->menus()->attach($request->menu_id);
    $role->permissions()->attach($request->permission_id);
    Helpers::alert('success','Role berhasil dibuat');
    return redirect('manage-role');
  }
  

  public function edit($id)
  {
    $role=Role::findOrFail($id);
    $role_menus=[];
    $role_permissions=[];
    $menus=Menu::where('parent_id',0)->get();
    foreach ($role->menus as $menu) {
      $role_menus[]=$menu->id_menu;
    }
    foreach ($role->permissions as $permission) {
      $role_permissions[]=$permission->id_permission;
    }
    return view('admin.roles.edit-roles-page',compact('role_menus','role_permissions','role','menus'));
  }

  public function update(Request $request, $id)
  {
    $role=Role::where('id_role',$id);
    $update=$role->update($request->only('nama_role','keterangan_role'));
    $role->first()->menus()->sync($request->menu_id);
    $role->first()->permissions()->sync($request->permission_id);
    Helpers::alert('success','Data role berhasil diperbaharui');
    return back();

  }

    
    public function destroy($id)
    {
      Role::find($id)->delete();
      Helpers::alert('success','Data role berhasil dihapus');
      return back();
    }
  }
