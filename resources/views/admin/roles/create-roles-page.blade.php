@extends('adminlte::page')

@section('title', 'Tambah Role Baru')

@section('content_header')
    <h1>Tambah role<small>Buat role user baru</small></h1>
    <ol class="breadcrumb">
      <li ><a href="{{url('manage-role')}}"><i class="fa fa-random"></i> Kelola Role</a></li>
      <li class="active"><a href="{{url('manage-role/create')}}"> Tambah Role</a></li>
    </ol>
@stop

@section('content')
  <div class="row">
    <div class="col-lg-12 col-sm-12">
      @if(Session::has('alert'))
        @include('layouts.alert')
      @endif
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Tambah Role Baru</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" method="post" action="{{url('manage-role')}}">
          @csrf
          <div class="box-body">
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 control-label">Nama Role</label>

              <div class="col-sm-7">
                <input class="form-control" id="inputPassword3" placeholder="Nama Role" type="text" name="nama_role">
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi</label>

              <div class="col-sm-7">
                <textarea class="form-control" name="keterangan_role" placeholder="Keterangan"></textarea>
              </div>
            </div>
            <div class="form-group">
              
              <div class="col-sm-12">
                <label for="inputEmail3">Roles Menu Permission</label>
                <ul class="checktree">
                   @foreach($menus as $menu)
                      @if(count($menu->submenus)=="0")
                        <li><input type="checkbox" name="menu_id[]" value="{{$menu->id_menu}}"> <b> {{$menu->nama_menu}}</b>
                          @if(count($menu->permissions) > 0)
                            <ul>
                              @foreach($menu->permissions as $permission)
                                <li>    
                                  <input type="checkbox" name="permission_id[]" value="{{$permission->id_permission}}" > {!!$permission->keterangan_permission.'<i>( '.$permission->permission.' )</i>'!!}          
                                </li>
                              @endforeach 
                            </ul>
                          @endif
                        </li>

                      @else
                          <li>
                              <i class="{{$menu->icon}}"></i>
                              <input type="checkbox" name="menu_id[]" value="{{$menu->id_menu}}"> <b> {{$menu->nama_menu}}</b> 
                              <ul>
                                  @foreach($menu->submenus as $submenu)
                                      
                                      @if(count($submenu->submenus)==0)
                                        <li>  
                                          <i class="{{$submenu->icon}}"></i>
                                          <input type="checkbox" name="menu_id[]" value="{{$submenu->id_menu}}" > <b> {{$submenu->nama_menu}}</b>  
                                          @if(count($submenu->permissions) > 0)
                                          <ul>
                                            @foreach($submenu->permissions as $permission)
                                              <li>    
                                                <input type="checkbox" name="permission_id[]" value="{{$permission->id_permission}}" > {!!$permission->keterangan_permission.'<i>( '.$permission->permission.' )</i>'!!}          
                                              </li>
                                            @endforeach 
                                          </ul>
                                          @endif
                                        </li>
                                      @else
                                        <li> 
                                          <i class="{{$submenu->icon}}"></i> 
                                          <input type="checkbox" name="menu_id[]" value="{{$submenu->id_menu}}" > <b> {{$submenu->nama_menu}}</b>
                                          <ul>
                                            @foreach($submenu->submenus as $submenu2)
                                              @if(count($submenu2->submenus)==0)
                                              <li>  
                                                <input type="checkbox" name="menu_id[]" value="{{$submenu2->id_menu}}" > <b> {{$submenu2->nama_menu}}</b>  
                                                @if(count($submenu2->permissions) > 0)
                                                <ul>
                                                  @foreach($submenu2->permissions as $permission)
                                                    <li>    
                                                      <input type="checkbox" name="permission_id[]" value="{{$permission->id_permission}}" > {!!$permission->keterangan_permission.'<i>( '.$permission->permission.' )</i>'!!}          
                                                    </li>
                                                  @endforeach 
                                                </ul>
                                                @endif
                                              </li>
                                              @endif
                                            @endforeach
                                          </ul>  
                                        </li>
                                      
                                      @endif
                                  @endforeach
                              </ul>
                          </li>
                      @endif
                   @endforeach
                </ul>
              </div>      
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-7">
                <button class="btn btn-sm btn-primary" type="submit"><i class="glyphicon glyphicon-floppy-disk"></i> Simpan</button>
              </div>
            </div>
          </div>
          <div class="box-footer">
            
          </div>
        </form>
      </div>
    </div>
  </div>
@stop

@section('css')
  @include('plugins.alertify-css')
  @include('plugins.simple-tree-css')
@stop

@section('js')
  @include('plugins.alertify-js')
    <script>

      function confirmation(id) {
        alertify.confirm("Confirmation!","Are sure to delete this data?",function(){
          $('#'+id).submit();
        },function(){

        })
      }
    </script>
@stop