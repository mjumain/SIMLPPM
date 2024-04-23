@extends('adminlte::page')

@section('title', 'Setting Carousel')

@section('content_header')
    <h1>Setting Carousel</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-fort-awesome"></i>Setting Halaman Depan</a></li>
      <li class="active"><a href="{{url('admin/setting-carousel')}}"> Setting Carousel</a></li>
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
                  <th width="30%">Gambar</th>
                  <th width="20%">Judul</th>
                  <th width="40%">Deskripsi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($datas as $data)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td><img src="{{url('/carousel/'.$data->file_gambar)}}" width="400px" height="175px;"></td>
                  <td>{{$data->teks_judul}}</td>
                  <td>{{$data->deskripsi}}</td>
                  <td>
                     <div class="dropdown ">
                        <button id="dLabel" class="btn btn-primary btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-angle-down"></i> Aksi
                      
                        </button>
                        <ul class=" dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                          
                          <li>
                              <a onclick="edit('{{$data->id_carousel}}')"> Edit</a>
                          </li>
                          <li>
                              <a onclick="hapus('{{$data->id_carousel}}')" > Delete</a>
                              <form action="{{url('admin/setting-carousel/'.$data->id_carousel)}}" method="post" id="{{$data->id_carousel}}">
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
                        <label class="control-label col-sm-3">Teks Judul</label>
                        <div class="col-sm-8">
                            <input type="text" name="teks_judul" id="teks_judul" class="form-control" placeholder="Teks Judul" required="">
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Deskripsi</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="deskripsi" id='deskripsi' rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">File Gambar</label>
                        <div class="col-sm-8">
                            <input type="file" name="file_gambar" id="file_gambar" class="form-control"  >
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
        var url=baseURL()+"/admin/setting-carousel";
        $('#modal-form').attr('action',url);
        $('#judul').html("<i class='fa fa-plus'></i> Tambah Cerousel");
        $('#teks_judul').val("");
        $('#deskripsi').html("");
        

        
      }
      function hapus(id) {
        alertify.confirm("Konfirmasi!","Apakah anda yakin menghapus carousel  ?",function(){
          $('#'+id).submit();
        },function(){

        })
      }
      function edit(id)
      {
         $.ajax({
            url:baseURL()+"/admin/setting-carousel/"+id+"/edit",
            type:'get',
            success:function(data)
            {
                $('#modal').modal('show');
                $('#method').html("<input type='hidden' name='_method' value='PUT'>");
                $('#judul').html("<i class='fa fa-edit'></i> Edit Carousel");
                var url=baseURL()+"/admin/setting-carousel/"+id;
                $('#modal-form').attr('action',url);
                $('#teks_judul').val(data.teks_judul);
                $('#deskripsi').html(data.deskripsi);
       
            }
        });
      }
     
    </script>
@stop