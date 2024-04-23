@extends('adminlte::page')

@section('title', 'Data Reviewer')

@section('content_header')
    <h1>Data Reviewer</h1>
    <ol class="breadcrumb">
      <li ><a href="#"><i class="fa fa-wrench"></i> Setup Aplikasi</a></li>
      <li class="active"><a href="{{url('/setup-reviewer')}}"> Reviewer</a></li>
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
                  <th width="50%">Nama Reviewer</th>
                  <th>Reviewer Proposal</th>
                  <th>Reviewer Evaluasi Hasil</th>
                  <th width="10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($datas as $data)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{!!"<u>".Helpers::nama_gelar($data->pegawai).'</u>'.'<br>'.$data->pegawai->nip !!}</td>
                  <td>[ {{$data->pegawai->reviewer_proposal->count() }}  ]</td>
                  <td>
                     [  {{$data->pegawai->reviewer_evaluasi_hasil->count() }}  ]
                  </td>
                  
                  <td>
                     <div class="dropdown ">
                        <button id="dLabel" class="btn btn-primary btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-angle-down"></i> Aksi
                      
                        </button>
                        <ul class=" dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                          
                          
                          <li>
                              <a onclick="hapus('{{$data->id_reviewer_tahun_anggaran}}','{!!Helpers::nama_gelar($data->pegawai)!!}')" > Delete</a>
                              <form action="{{url('setup-reviewer/'.$data->id_reviewer_tahun_anggaran)}}" method="post" id="{{$data->id_reviewer_tahun_anggaran}}">
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
            <form action="{{url('setup-reviewer')}}" id="modal-form" method="post" class="form-horizontal">
                @csrf
                
                <div class="modal-header">
                    <div class="modal-title">
                        Tambah Reviewer
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Reviewer</label>
                        <div class="col-sm-8">
                            <select name="pegawai_id" id="pegawai_id" class="form-control" required="" style="width: 100%">
                            
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
     
       $("#id_tkt").select2();
       
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
        
      }
      function hapus(id,rev) {
        alertify.confirm("Konfirmasi!","Apakah anda yakin menghapus reviewer "+rev+"  dari daftar reviewer?",function(){
          $('#'+id).submit();
        },function(){

        })
      }
       $("#pegawai_id").select2({
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