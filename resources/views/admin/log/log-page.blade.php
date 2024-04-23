@extends('adminlte::page')

@section('title', 'Laporan Log Aktivitas User')

@section('content_header')
    <h1>Laporan Log Aktivitas User</h1>
    <ol class="breadcrumb">
      <li class="active"><a href="{{url('admin/log-aktivitas')}}"><i class="fa fa-clock-o"></i> Log Aktivitas</a></li>
    </ol>
@stop

@section('content')
  <div class="row">
    <div class="col-lg-12 col-xs-12">
      @if(Session::has('alert'))
        @include('layouts.alert')
      @endif
      
      <div class="box box-info">
        <div class="box-header with-border" align="center">
          <b>Log Aktivitas</b>
        </div>
        <div class="box-body">
          
          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="table-data">
              <thead>
                <tr>
                  <th width="2%">No</th><th width="10%">Username</th><th width="20%">Nama Dosen/Pegawai</th><th width="15%">IP Address</th><th width="15%">Waktu</th>
                  <th width="20%">User Agent</th>
                  <th width="20%">Aktivitas</th>
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
  @include('plugins.datepicker-css')
  <style type="text/css">
    .popover{
      max-width: 100%;
    }
  </style>
@stop

@section('js')
  @include('plugins.alertify-js')
  
  <script type="text/javascript">
     function load_data()
     {
      var table=$('#table-data').DataTable( {
          bAutoWidth: false,
          bLengthChange: true,
          iDisplayLength: 10,
          searching: true,
          processing: true,
          serverSide: true,
          bDestroy: true,
          bSavestate:true,
          
          ajax: {
            data:function(d){
              d.tgl_awal=$("input[name=tgl_awal]").val();
              d.tgl_akhir=$("input[name=tgl_akhir]").val();
            },
            url:'{{url('log-aktivitas')}}'
          },
          columns: [
                {data: 'DT_RowIndex',orderable:false,searchable:false},
                {data: 'id_pelaku', name: 'id_pelaku'},
                {data: 'nama_pelaku', name: 'nama_pelaku'},
                {data: 'ip', name: 'ip'},
                {data: 'tanggal', name: 'tanggal'},
                {data: 'user_agent', name: 'user_agent'},
                {data: 'aktifitas', name: 'aktifitas'},
                
                
            ],
          
          aLengthMenu: [[10, 15, 25, 35, 50, 100, -1], [10, 15, 25, 35, 50, 100, "All"]], 
          responsive: !0
        });
     }
     load_data();
     
  </script>
  @include('plugins.datepicker-js')
  <script type="text/javascript">
     var datepicker = $.fn.datepicker.noConflict(); // return $.fn.datepicker to previously assigned value
    $.fn.bootstrapDP = datepicker;                 // give $().bootstrapDP the bootstrap-datepicker functionality
  </script>
@stop