@extends('adminlte::page')

@section('title', 'Kelola Role')

@section('content_header')
    <h1>Kelola Role<small>Kelola darftar role user</small></h1>
    <ol class="breadcrumb">
      <li class="active"><a href="{{url('admin/manage-role')}}"><i class="fa fa-random"></i> Kelola role</a></li>
    </ol>
@stop

@section('content')
  <div class="row">
    <div class="col-lg-12 col-xs-12">
      @if(Session::has('alert'))
        @include('layouts.alert')
      @endif
      <div class="box box-info">
        <div class="box-header with-border">
          <div class="pull-right">
            <a href="{{url('manage-role/create')}}"class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Role</a>
          </div>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="table">
              <thead>
                <tr>
                  <th width="2%">No</th><th>Nama Role</th><th>Keterangan</th><th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($roles as $role)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$role->nama_role}}</td>
                  <td>{{$role->keterangan_role}}</td>
                  <td>
                    <a href="{{url('manage-role/'.$role->id_role.'/edit')}}" class="btn btn-xs btn-info"><i class="fa fa-edit"></i> Edit</a> | 
                    <a onclick="confirmation('{{$role->id_role}}')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>
                    <form id="{{$role->id_role}}" action="{{url('manage-role/'.$role->id_role)}}" method="post">
                      @csrf
                      @method('DELETE')
                    </form>
                  </td>
                </tr>
                @empty
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
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

       var table=$('#table').DataTable( {
          bAutoWidth: false,
          bLengthChange: true,
          iDisplayLength: 10,
          searching: true,
          processing: false,
          serverSide: false,
          aLengthMenu: [[10, 15, 25, 35, 50, 100, -1], [10, 15, 25, 35, 50, 100, "All"]], 
          responsive: !0
        });
      function confirmation(id) {
        alertify.confirm("Konfirmasi!","Apakah anda yakin menghapus data role ini?",function(){
          $('#'+id).submit();
        },function(){

        })
      }
    </script>
@stop