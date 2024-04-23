@extends('adminlte::page')

@section('title', 'Manage User')

@section('content_header')
    <h1>Kelola User<small>Kelola pengguna sistem</small></h1>
    <ol class="breadcrumb">
      <li class="active"><a href="{{url('manage-user')}}"><i class="fa fa-user"></i> Kelola user</a></li>
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
            <a href="{{url('manage-user/create')}}"class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah User</a>
          </div>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="table">
              <thead>
                <tr>
                  <th>No</th><th>Username</th><th>Nama </th><th>NIP</th><th>Roles</th><th>Created at</th><th>Action</th>
                </tr>
              </thead>
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
          processing: true,
          serverSide: true,
          bDestroy: true,
          bStateSave: true,
          ajax: {
            url:'{{url('manage-user')}}'
          },
          columns: [
                {data: 'DT_RowIndex',orderable:false,searchable:false},
                
                {data: 'username', name: 'username'},
                {data: 'nama_gelar',name:'nama_gelar',orderable:false,searchable:false},
                {data: 'nip',name:'a.nip'},
                {data: 'roles', name: 'roles',orderable:false,searchable:false},
                
                {data: 'created_at', name: 'created_at',orderable:false,searchable:false},
                {data: 'action', name: 'action',orderable:false,searchable:false},
                {data: 'nama_lengkap',name:'a.nama_lengkap',visible:false},
            ],
          aLengthMenu: [[10, 15, 25, 35, 50, 100, -1], [10, 15, 25, 35, 50, 100, "All"]], 
          responsive: !0
        });
      function confirmation(id) {
        alertify.confirm("Konfirmasi!","Apakah anda yakin menghapus data ini?",function(){
          $('#'+id).submit();
        },function(){

        })
      }
    </script>
@stop