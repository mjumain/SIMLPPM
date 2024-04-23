@extends('adminlte::page')

@section('title', 'Data Jenis Skema')

@section('content_header')
    <h1>Data Jenis Skema</h1>
    <ol class="breadcrumb">
      <li ><a href="#"><i class="fa fa-clone"></i> Master Data</a></li>
      <li class="active"><a href="{{url('/master-jenis-skema')}}"> Jenis Skema</a></li>
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
                  <th width="15%">Jenis Usulan</th>
                  <th>Skema</th>
                  <th width="15%">TKT</th>
                  <th>Luaran Wajib</th>
                  <th>Luaran Tambahan</th>
                  <th width="10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($datas as $data)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$data->jenis_usulan->jenis_usulan }}</td>
                  <td>{{$data->nama_jenis_skema }}</td>
                  <td>
                     <ul>
                       @foreach($data->tkt as $t)
                       <li>{{$t->nilai_tkt}}</li>
                       @endforeach
                     </ul>
                  </td>
                  <td>
                     <ul>
                       @foreach($data->luaran_wajib as $t)
                       <li>{{$t->nama_luaran}}</li>
                       @endforeach
                     </ul>
                  </td>
                  <td>
                     <ul>
                       @foreach($data->luaran_tambahan as $t)
                       <li>{{$t->nama_luaran}}</li>
                       @endforeach
                     </ul>
                  </td>
                  <td>
                     <div class="dropdown ">
                        <button id="dLabel" class="btn btn-primary btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-angle-down"></i> Aksi
                      
                        </button>
                        <ul class=" dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                          
                          <li>
                              <a onclick="edit('{{$data->id_jenis_skema}}')"> Edit</a>
                          </li>
                          <li>
                              <a onclick="hapus('{{$data->id_jenis_skema}}','{{$data->nama_jenis_skema}}')" > Delete</a>
                              <form action="{{url('master-jenis-skema/'.$data->id_jenis_skema)}}" method="post" id="{{$data->id_jenis_skema}}">
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
                        <label class="control-label col-sm-3">Jenis Usulan</label>
                        <div class="col-sm-8">
                            <select name="jenis_usulan_id" id="jenis_usulan_id" class="form-control" required="">
                              <option value="">Pilih Jenis Usulan</option>
                              @foreach(App\JenisUsulan::get() as $j )
                              <option value="{{$j->id_jenis_usulan}}">{{$j->jenis_usulan}}</option>
                              @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Nama Jenis Skema</label>
                        <div class="col-sm-8">
                            <input type="text" name="nama_jenis_skema" id="nama_jenis_skema" class="form-control" placeholder="Nama Jenis Skema" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">TKT</label>
                        <div class="col-sm-8">
                            <select name="id_tkt[]" id="id_tkt" class="form-control select2" required="" multiple="" style="width: 100%;">
                              
                              @foreach(App\TKT::get() as $j )
                              <option value="{{$j->id_tkt}}">{{$j->nilai_tkt.' '.$j->keterangan}}</option>
                              @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Luaran Wajib</label>
                        <div class="col-sm-8">
                            <select name="id_luaran_wajib[]" id="id_luaran_wajib" class="form-control select2" required="" multiple="" style="width: 100%;">
                              
                              @foreach(App\Luaran::get() as $j )
                              <option value="{{$j->id_luaran}}">{{$j->nama_luaran}}</option>
                              @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Luaran Tambahan</label>
                        <div class="col-sm-8">
                            <select name="id_luaran_tambahan[]" id="id_luaran_tambahan" class="form-control select2" required="" multiple="" style="width: 100%;">
                              
                              @foreach(App\Luaran::get() as $j )
                              <option value="{{$j->id_luaran}}">{{$j->nama_luaran}}</option>
                              @endforeach
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
       $("#id_tkt").select2();
       $("#id_luaran_wajib").select2();
       $("#id_luaran_tambahan").select2();
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
        var url=baseURL()+"/master-jenis-skema";
        $('#modal-form').attr('action',url);
        $('#judul').html("<i class='fa fa-plus'></i> Tambah Jenis Skema");
        $('#jenis_usulan_id').val("");
        $('#nama_jenis_skema').val("");
        $('#id_tkt').val("");
        $('#id_tkt').trigger('change');
        $('#id_luaran_wajib').val("");
        $('#id_luaran_wajib').trigger('change');
        $('#id_luaran_tambahan').val("");
        $('#id_luaran_tambahan').trigger('change');
      }
      function hapus(id,skema) {
        alertify.confirm("Konfirmasi!","Apakah anda yakin menghapus jenis skema "+skema+" ?",function(){
          $('#'+id).submit();
        },function(){

        })
      }
      function edit(id)
      {
         $.ajax({
            url:baseURL()+"/master-jenis-skema/"+id+"/edit",
            type:'get',
            success:function(data)
            {
                $('#modal').modal('show');
                $('#method').html("<input type='hidden' name='_method' value='PUT'>");
                $('#judul').html("<i class='fa fa-edit'></i> Edit Jenis Skema");
                var url=baseURL()+"/master-jenis-skema/"+id;
                $('#modal-form').attr('action',url);
                
                $('#jenis_usulan_id').val(data.jenis_skema.jenis_usulan_id);
                $('#nama_jenis_skema').val(data.jenis_skema.nama_jenis_skema);
                $('#id_tkt').val(data.tkt);
                $('#id_tkt').trigger('change');
                $('#id_luaran_wajib').val(data.luaran_wajib);
                $('#id_luaran_wajib').trigger('change');
                $('#id_luaran_tambahan').val(data.luaran_tambahan);
                $('#id_luaran_tambahan').trigger('change');
                        
            }
        });
      }
      
    </script>
@stop