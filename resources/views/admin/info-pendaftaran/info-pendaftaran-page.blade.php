@extends('adminlte::page')

@section('title', 'Artikel Info Pendaftaran')

@section('content_header')
    <h1>Info Pendaftaran</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-clone"></i>Artikel</a></li>
      <li class="active"><a href="{{url('info-pendaftaran')}}"> Info Pendaftaran</a></li>
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
            <a href="{{url('info-pendaftaran/create')}}"  class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah</a>
          </div>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="table">
              <thead>
                <tr>
                  <th width="2%">No</th>
                  <th >Judul</th>
                  
                  <th width="10%">Status</th>

                  <th width="10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($datas as $data)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$data->judul}}</td>
                  
                  <td>
                    @if($data->status_info=='draft')
                    <span class='label label-primary'>Draf</span>
                    @elseif($data->status_info=='published')
                    <span class='label label-success'>Published</span>
                    @endif
                  </td>
                  <td>
                     <div class="dropdown ">
                        <button id="dLabel" class="btn btn-primary btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-angle-down"></i> Aksi
                      
                        </button>
                        <ul class=" dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                          
                          <li>
                              <a href="{{url('info-pendaftaran/'.$data->id_info_pendaftaran.'/edit')}}"> Edit</a>
                          </li>
                          <li>
                              <a onclick="hapus('{{$data->id_info_pendaftaran}}')" > Delete</a>
                              <form action="{{url('info-pendaftaran/'.$data->id_info_pendaftaran)}}" method="post" id="{{$data->id_info_pendaftaran}}">
                                  @csrf
                                  @method('DELETE')
                              </form>
                          </li>
                        </ul>
                      </div>
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
  
  <div class="modal fade" id="modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="" id="modal-form" method="post" class="form-horizontal">
                @csrf
                <div id="method"></div>
                <div class="modal-header">
                    <div class="modal-title">
                        <h3 id="judul"></h3>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">ID Agama</label>
                        <div class="col-sm-8">
                            <input type="text" name="id_agama" id="id_agama" class="form-control" placeholder="ID Agama" required="">
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Nama Agama</label>
                        <div class="col-sm-8">
                            <input type="text" name="nama_agama" id="nama_agama" class="form-control" placeholder="Nama Agama" required="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Batal</button>
                    <button  class="btn btn-sm btn-info"><i class="glyphicon glyphicon-floppy-disk"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
  </div>
  @stop

@section('css')
  @include('plugins.alertify-css')
  @include('plugins.icon-picker-css')
@stop

@section('js')
  @include('plugins.alertify-js')
  @include('plugins.icon-picker-js')
    <script>
      $('.button-icon').on('change', function(e) {
          $('.icon').val("fa "+e.icon);
      });
       $(".select2").select2();
       var table=$('#table').DataTable( {
         
          bLengthChange: true,
          iDisplayLength: 10,
          searching: true,
          processing: false,
          serverSide: false,
          aLengthMenu: [[5,10, 15, 25, 35, 50, 100, -1], [5,10, 15, 25, 35, 50, 100, "All"]], 
          responsive: !0,
          bStateSave:true
          
        });
      
      function hapus(id) {
        alertify.confirm("Konfirmasi!","Apakah anda yakin menghapus data info pendaftaran ini?",function(){
          $('#'+id).submit();
        },function(){

        })
      }
     
     
    </script>
@stop