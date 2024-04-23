@extends('adminlte::page')

@section('title', 'Data Unit Kerja')

@section('content_header')
    <h1>Data Unit Kerja</h1>
    <ol class="breadcrumb">
      <li ><a href="#"><i class="fa fa-clone"></i> Master Data</a></li>
      <li class="active"><a href="{{url('/master-unit-kerja')}}"> Unit Kerja</a></li>
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
          @if(auth()->user()->cek_akses('create-unit-kerja'))
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
                  <th width="10%">Kode Unit</th>
                  <th>Nama Unit</th>
                  
                  <th width="10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($datas as $data)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$data->kode_unit}}</td>
                  <td>{{$data->nama_unit }}</td>
                  
                  <td>

                     <div class="dropdown ">
                        <button id="dLabel" class="btn btn-primary btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-angle-down"></i> Aksi
                      
                        </button>
                        <ul class=" dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                          @if(auth()->user()->cek_akses('update-unit-kerja'))
                          <li>
                              <a onclick="edit('{{$data->id_unit_kerja}}')"> Edit</a>
                          </li>
                          @endif
                          @if(auth()->user()->cek_akses('delete-unit-kerja'))
                          <li>
                              <a onclick="hapus('{{$data->id_unit_kerja}}','{{$data->nama_unit}}')" > Delete</a>
                              <form action="{{url('master-unit-kerja/'.$data->id_unit_kerja)}}" method="post" id="{{$data->id_unit_kerja}}">
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
                        <label class="control-label col-sm-3">Kode Unit Kerja</label>
                        <div class="col-sm-8">
                            <input type="text" name="kode_unit" id="kode_unit" class="form-control" placeholder="Kode Unit Kerja" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Nama Unit Kerja</label>
                        <div class="col-sm-8">
                            <input type="text" name="nama_unit" id="nama_unit" class="form-control" placeholder="Nama Unit Kerja" required="">
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
        var url=baseURL()+"/master-unit-kerja";
        $('#modal-form').attr('action',url);
        $('#judul').html("<i class='fa fa-plus'></i> Tambah Unit Kerja");
        $('#kode_unit').val("");
        $('#nama_unit').val("");
      }
      function hapus(id,unit) {
        alertify.confirm("Konfirmasi!","Apakah anda yakin menghapus unit "+unit+" ?",function(){
          $('#'+id).submit();
        },function(){

        })
      }
      function edit(id)
      {
         $.ajax({
            url:baseURL()+"/master-unit-kerja/"+id+"/edit",
            type:'get',
            success:function(data)
            {
                $('#modal').modal('show');
                $('#method').html("<input type='hidden' name='_method' value='PUT'>");
                $('#judul').html("<i class='fa fa-edit'></i> Edit Unit Kerja");
                var url=baseURL()+"/master-unit-kerja/"+id;
                $('#modal-form').attr('action',url);
                $('#kode_unit').val(data.kode_unit);
                $('#nama_unit').val(data.nama_unit);
                        
            }
        });
      }
      
    </script>
@stop