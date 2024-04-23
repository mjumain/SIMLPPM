@extends('adminlte::page')

@section('title', 'Data Sumber Dana')

@section('content_header')
    <h1>Data Sumber Dana</h1>
    <ol class="breadcrumb">
      <li ><a href="#"><i class="fa fa-clone"></i> Master Data</a></li>
      <li class="active"><a href="{{url('/master-sumber-dana')}}"> Sumber Dana</a></li>
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
          @if(auth()->user()->cek_akses('create-sumber-dana'))
          <div class="pull-right">
            <a onclick="tambah()"  class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah</a>
          </div>
          @endif
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="table">
              <thead>
                <tr>
                  <th width="2%">No</th>
                  <th width="10%">Kode</th>
                  <th>Nama Sumber Dana</th>
                  
                  <th width="10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($datas as $data)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$data->kode_sumber_dana}}</td>
                  <td>{{$data->nama_sumber_dana }}</td>
                  
                  <td>
                     <div class="dropdown ">
                        <button id="dLabel" class="btn btn-primary btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-angle-down"></i> Aksi
                      
                        </button>
                        <ul class=" dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                          @if(auth()->user()->cek_akses('update-sumber-dana'))
                          <li>
                              <a onclick="edit('{{$data->id_sumber_dana}}')"> Edit</a>
                          </li>
                          @endif
                          @if(auth()->user()->cek_akses('delete-sumber-dana'))
                          <li>
                              <a onclick="hapus('{{$data->id_sumber_dana}}','{{$data->nama_unit}}')" > Delete</a>
                              <form action="{{url('master-sumber-dana/'.$data->id_sumber_dana)}}" method="post" id="{{$data->id_sumber_dana}}">
                                  @csrf
                                  @method('DELETE')
                              </form>
                          </li>
                          @endif
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
                        <label class="control-label col-sm-4">Kode Sumber Dana</label>
                        <div class="col-sm-7">
                            <input type="text" name="kode_sumber_dana" id="kode_sumber_dana" class="form-control" placeholder="Kode Sumber Dana" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Nama Sumber Dana</label>
                        <div class="col-sm-7">
                            <input type="text" name="nama_sumber_dana" id="nama_sumber_dana" class="form-control" placeholder="Nama Sumber Dana" required="">
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
        var url=baseURL()+"/master-sumber-dana";
        $('#modal-form').attr('action',url);
        $('#judul').html("<i class='fa fa-plus'></i> Tambah Sumber Dana");
        $('#kode_sumber_dana').val("");
        $('#nama_sumber_dana').val("");
      }
      function hapus(id,sumber) {
        alertify.confirm("Konfirmasi!","Apakah anda yakin menghapus sumber dana "+sumber+" ?",function(){
          $('#'+id).submit();
        },function(){

        })
      }
      function edit(id)
      {
         $.ajax({
            url:baseURL()+"/master-sumber-dana/"+id+"/edit",
            type:'get',
            success:function(data)
            {
                $('#modal').modal('show');
                $('#method').html("<input type='hidden' name='_method' value='PUT'>");
                $('#judul').html("<i class='fa fa-edit'></i> Edit Sumber Dana");
                var url=baseURL()+"/master-sumber-dana/"+id;
                $('#modal-form').attr('action',url);
                $('#kode_sumber_dana').val(data.kode_sumber_dana);
                $('#nama_sumber_dana').val(data.nama_sumber_dana);
                        
            }
        });
      }
      
    </script>
@stop