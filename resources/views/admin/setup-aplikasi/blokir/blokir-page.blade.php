@extends('adminlte::page')

@section('title', 'Data Blokir User')

@section('content_header')
    <h1>Data Blokir</h1>
    <ol class="breadcrumb">
      <li ><a href="#"><i class="fa fa-wrench"></i> Setup Aplikasi</a></li>
      <li class="active"> Blokir</li>
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
                  
                  <th>Nama Dosen/Pegawai</th>
                  <th>Alasan Blokir</th>
                  <th>Status Blokir</th>
                  <th>Tanggal Blokir</th>
                  <th width="10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($datas as $data)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  
                  <td><u>{{Helpers::nama_gelar($data->pegawai) }}</u><br>{{$data->pegawai->nip}}</td>
                  <td>{{$data->alasan}}</td>
                  <td>{{$data->status_blokir==1 ? 'Sedang Diblok':'Tidak Diblok'}}</td>
                  <td>{{Tanggal::time_indo($data->created_at)}}</td>
                  <td>
                     <div class="dropdown ">
                        <button id="dLabel" class="btn btn-primary btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-angle-down"></i> Aksi
                      
                        </button>
                        <ul class=" dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                          
                          <li>
                              <a onclick="edit('{{$data->id_blokir}}')"> Edit</a>
                          </li>
                          <li>
                              <a onclick="hapus('{{$data->id_blokir}}','{{Helpers::nama_gelar($data->pegawai)}}')" > Delete</a>
                              <form action="{{url('blokir/'.$data->id_blokir)}}" method="post" id="{{$data->id_blokir}}">
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
    <div class="modal-dialog modal-lg">
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
                        <label class="control-label col-sm-3">Nama Pegawai/Dosen</label>
                        <div class="col-sm-8">
                            <select name="id_peg" id="id_peg" style="width: 100%" class="form-control" required="" >
                            
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Alasan Blokir</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="alasan" name="alasan" rows="5"></textarea>
                            
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Status Blokir</label>
                        <div class="col-sm-8">
                            <select name="status_blokir" id="status_blokir" class="form-control" required="" >
                            <option value="0">Tidak Diblokir</option>
                            <option value="1">Sedang Biblokir</option>
                            </select>
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
        var url=baseURL()+"/blokir";
        $('#modal-form').attr('action',url);
        $('#judul').html("<i class='fa fa-plus'></i> Tambah Data User Blokir");
        
        $('#id_peg').val("");
        $('#alasan').html("");
        $('#status_blokir').val("");
      }
      function hapus(id,peg) {
        alertify.confirm("Konfirmasi!","Apakah anda yakin menghapus  "+peg+" dari daftar blokir?",function(){
          $('#'+id).submit();
        },function(){

        })
      }
      function edit(id)
      {
         $.ajax({
            url:baseURL()+"/blokir/"+id+"/edit",
            type:'get',
            success:function(data)
            {
                $('#modal').modal('show');
                $('#method').html("<input type='hidden' name='_method' value='PUT'>");
                $('#judul').html("<i class='fa fa-edit'></i> Edit Blokir");
                var url=baseURL()+"/blokir/"+id;
                $('#modal-form').attr('action',url);
                
                $('#id_peg').append("<option selected value="+data.id_peg+">"+data.nip+" - "+data.nama_peg+"<option>");
                $('#alasan').html(data.alasan);
                $('#status_blokir').val(data.status_blokir);
                        
            }
        });
      }
      $("#id_peg").select2({
          placeholder:"Tentukan dosen atau pegawai..",
          ajax:{
              url:"{{url('load-dosen-pegawai')}}",
              dataTyper:"json",
              data:function(param)
              {
                  var value= {
                      search:param.term,
                  }
                  return value;
              },
              processResults:function(hasil)
              {
                  return {
                      results:hasil,
                  }
              }
          }
        });
      
    </script>
@stop