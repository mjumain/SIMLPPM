@extends('adminlte::page')

@section('title', 'Setting Step Pendaftaran')

@section('content_header')
    <h1>Setting Step Pendaftaran</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-fort-awesome"></i>Setting Halaman Depan</a></li>
      <li class="active"><a href="{{url('admin/setting-step-pendaftaran')}}"> Setting Step Pendaftaran</a></li>
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
            <a onclick="tambah()"  class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah</a>
          </div>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="table">
              <thead>
                <tr>
                  <th width="2%">No</th>
                  <th width="10%">Urutan</th>
                  <th width="30%">Judul</th>
                  
                  <th width="50%">Deskripsi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($datas as $data)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$data->urutan}}</td>
                  <td>{{$data->judul}}</td>
                  <td>{{$data->deskripsi}}</td>
                  <td>
                     <div class="dropdown ">
                        <button id="dLabel" class="btn btn-primary btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-angle-down"></i> Aksi
                      
                        </button>
                        <ul class=" dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                          
                          <li>
                              <a onclick="edit('{{$data->id_step_pendaftaran}}')"> Edit</a>
                          </li>
                          <li>
                              <a onclick="hapus('{{$data->id_step_pendaftaran}}')" > Delete</a>
                              <form action="{{url('admin/setting-step-pendaftaran/'.$data->id_step_pendaftaran)}}" method="post" id="{{$data->id_step_pendaftaran}}">
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
            <form action="" id="modal-form" method="post" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div id="method"></div>
                <div class="modal-header">
                    <div class="modal-title">
                        <h3 id="judul"></h3>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Judul Step</label>
                        <div class="col-sm-8">
                            <input type="text" name="judul" id="judul_step" class="form-control" placeholder="Teks Judul" required="">
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Deskripsi</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="deskripsi" id='deskripsi' rows="5" required=""></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Urutan</label>
                        <div class="col-sm-8">
                            <input type="number" name="urutan" id="urutan" required="" class="form-control"  >
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
      function tambah()
      {
        $('#modal').modal('show');
        $('#method').html("");
        var url=baseURL()+"/admin/setting-step-pendaftaran";
        $('#modal-form').attr('action',url);
        $('#judul').html("<i class='fa fa-plus'></i> Tambah Step");
        $('#judul_step').val("");
        $('#deskripsi').html("");
        $('#urutan').val("");
        

        
      }
      function hapus(id) {
        alertify.confirm("Konfirmasi!","Apakah anda yakin menghapus data step ini  ?",function(){
          $('#'+id).submit();
        },function(){

        })
      }
      function edit(id)
      {
         $.ajax({
            url:baseURL()+"/admin/setting-step-pendaftaran/"+id+"/edit",
            type:'get',
            success:function(data)
            {
                $('#modal').modal('show');
                $('#method').html("<input type='hidden' name='_method' value='PUT'>");
                $('#judul').html("<i class='fa fa-edit'></i> Edit Step Pendaftaran");
                var url=baseURL()+"/admin/setting-step-pendaftaran/"+id;
                $('#modal-form').attr('action',url);
                $('#judul_step').val(data.judul);
                $('#urutan').val(data.urutan);
                $('#deskripsi').html(data.deskripsi);
       
            }
        });
      }
     
    </script>
@stop