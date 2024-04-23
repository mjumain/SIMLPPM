@extends('adminlte::page')

@section('title', 'Manage User')

@section('content_header')
    <h1>Edit User<small>Edit data user</small></h1>
    <ol class="breadcrumb">
      <li ><a href="{{url('manage-user')}}"><i class="fa fa-user"></i> Kelola User</a></li>
      <li class="active"> Edit user</li>
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
          <h3 class="box-title">Edit data user</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" method="post" action="{{url('manage-user/'.$user->id_user)}}">
          @csrf
          @method('PATCH')
          <div class="box-body">
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-3 control-label">Username</label>

              <div class="col-sm-7">
                <input type="text" class="form-control" name="username" value="{{$user->username}}">
              </div>
            </div>
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-3 control-label">Nama Pegawai</label>

              <div class="col-sm-7">
                <input type="text" class="form-control" disabled="" value="{{Helpers::nama_gelar($user->pegawai)}}">
              </div>
            </div>
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-3 control-label">Password (kosongkan jika tidak ingin dirubah)</label>

              <div class="col-sm-7">
                <input type="text" class="form-control" name="password"  value="" placeholder="Password baru">
              </div>
            </div>
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-3 control-label">Jenis Akun</label>

              <div class="col-sm-7">
                <select name="jenis_akun" id="jenis_akun" class="form-control" required="" >
                    <option value="tendik" {{$user->jenis_akun=='tendik' ? 'selected':''}}>Tendik</option>
                    <option value="dosen" {{$user->jenis_akun=='dosen' ? 'selected':''}}>Dosen</option>
                </select>
              </div>
            </div>
            
           
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-3 control-label">Role</label>
              <div class="col-sm-7">
                <div class="checkbox">
                  @foreach($roles as $role)
                  <label class="col-sm-5">
                    <input type="checkbox" name="roles[]" value="{{$role->id_role}}" {{in_array($role->id_role,$user_roles) ? "checked":""}}> {{$role->nama_role}}
                  </label>
                  @endforeach
                </div>
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
      $(".select2").select2();
       
    </script>
@stop