<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Tanggal;
use App\Permission;
use Helpers;
use App\Menu;
class MenuController extends Controller
{
  
  function __construct()
  {  
    $this->middleware('permission:read-menu')->only('index','show');
    $this->middleware('permission:create-menu')->only('create','store');
    $this->middleware('permission:update-menu')->only('edit','update');
    $this->middleware('permission:delete-menu')->only('destroy');
    
  }
  public function index()
  {
    $menus=Menu::all();
    return view('admin.menu.menus-page',compact('menus'));
  }

  
  public function store(Request $request)
  {
    Menu::create($request->all());
    Helpers::alert('success','Menu berhasil dibuat ');
    return back();
  }

  public function edit($id)
  {
    $menu=Menu::findOrFail($id);
    return response()->json($menu);
  }

  
  public function update(Request $request, $id)
  {
    $menu=Menu::findOrFail($id);
    $update=$menu->update($request->except('_token','_method'));
    Helpers::alert('success','Menu berhasil diperbaharui');
    return back();
  }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      Menu::find($id)->delete();
      Helpers::alert('success','Menu berhasil dihapus');
      return back();
    }
    public function createPermission(Request $request)
    {
      Permission::create($request->all());
      Helpers::alert('success','Permission berhasil dibuat');
      return back();
    }
  }
