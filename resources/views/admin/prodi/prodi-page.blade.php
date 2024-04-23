@extends('adminlte::page')

@section('title', 'Master data program studi')

@section('content_header')
    <h1>Data Program Studi</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-clone"></i>Master Data</a></li>
      <li class="active"><a href="{{url('admin/master-data-prodi')}}"> Data Prodi</a></li>
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
                  <th>Kode</th>
                  <th>Nama Prodi</th>
                  <th>Jenjang</th>
                  <th>Fakultas</th>
                  <th>Gelar</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($datas as $data)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$data->id_prodi}}</td>
                  <td>{{$data->nama_prodi }}</td>
                  <td>{{$data->jenjang_pendidikan->nama_jenjang_pendidikan}}</td>
                  <td>{{$data->fakultas->sebutan." ".$data->fakultas->nama_fakultas}}</td>
                  <td>{{$data->gelar_ijazah}}</td>
                  <td>
                     <div class="dropdown ">
                        <button id="dLabel" class="btn btn-primary btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-angle-down"></i> Aksi
                      
                        </button>
                        <ul class=" dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                          
                          <li>
                              <a onclick="edit('{{$data->id_prodi}}')"> Edit</a>
                          </li>
                          <li>
                              <a onclick="hapus('{{$data->id_prodi}}','{{$data->nama_prodi}}')" > Delete</a>
                              <form action="{{url('admin/master-data-prodi/'.$data->id_prodi)}}" method="post" id="{{$data->id_prodi}}">
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
                        <label class="control-label col-sm-3">Kode Prodi</label>
                        <div class="col-sm-8">
                            <input type="text" name="id_prodi" id="id_prodi" class="form-control" placeholder="Kode Prodi" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Nama Prodi</label>
                        <div class="col-sm-8">
                            <input type="text" name="nama_prodi" id="nama_prodi" class="form-control" placeholder="Nama Prodi" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Gelar Ijazah</label>
                        <div class="col-sm-8">
                            <input type="text" name="gelar_ijazah" id="gelar_ijazah" class="form-control" placeholder="Gelar Ijazah" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Jenjang</label>
                        <div class="col-sm-8">
                            <select class="form-control " id="id_jenjang_pendidikan" name="id_jenjang_pendidikan" >
                                @foreach($jenjang as $j)
                                <option value="{{$j->id_jenjang_pendidikan}}">{{$j->nama_jenjang_pendidikan}}</option>
                                @endforeach
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Website</label>
                        <div class="col-sm-8">
                            <input type="text" name="website" id="website" class="form-control" placeholder="Website Program Studi" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Fakultas</label>
                        <div class="col-sm-8">
                            <select class="form-control " id="id_fakultas" name="id_fakultas" >
                                @foreach($fakultas as $f)
                                <option value="{{$f->id_fakultas}}">{{$f->sebutan." ".$f->nama_fakultas}}</option>
                                @endforeach
                                
                            </select>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="control-label col-sm-3">Fakultas OTK</label>
                        <div class="col-sm-8">
                            <select class="form-control " id="id_fakultas_otk" name="id_fakultas_otk" >
                                <option value="0">Tidak Ada</option>
                                @foreach($fakultas as $f)
                                <option value="{{$f->id_fakultas}}">{{$f->sebutan." ".$f->nama_fakultas}}</option>
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
        var url=baseURL()+"/admin/master-data-prodi";
        $('#modal-form').attr('action',url);
        
        $('#judul').html("<i class='fa fa-plus'></i> Tambah Program Studi");
        $('#id_prodi').val("");
        $('#nama_prodi').val("");
        $('#gelar_ijazah').val("");
        $("#id_jenjang_pendidikan option[selected]").prop('selected',false);
        $("#id_fakultas option[selected]").prop('selected',false);
        $("#id_fakultas_otk option[selected]").prop('selected',false);     
        
      }
      function hapus(id,prodi) {
        alertify.confirm("Konfirmasi!","Apakah anda yakin menghapus prodi "+prodi+" ?",function(){
          $('#'+id).submit();
        },function(){

        })
      }
      function edit(id)
      {
         $.ajax({
            url:baseURL()+"/admin/master-data-prodi/"+id+"/edit",
            type:'get',
            success:function(data)
            {
                $('#modal').modal('show');
                $('#method').html("<input type='hidden' name='_method' value='PUT'>");
                $('#judul').html("<i class='fa fa-edit'></i> Edit Program Studi");
                var url=baseURL()+"/admin/master-data-prodi/"+id;
                $('#modal-form').attr('action',url);
                $('#id_prodi').val(data.id_prodi);
                $('#nama_prodi').val(data.nama_prodi);
                $('#gelar_ijazah').val(data.gelar_ijazah);
                $('#website').val(data.website);
                $("#id_jenjang_pendidikan option[value='"+data.id_jenjang_pendidikan+"']").prop('selected',true);
                $("#id_fakultas option[value='"+data.id_fakultas+"']").prop('selected',true);
                $("#id_fakultas_otk option[value='"+data.id_fakultas_otk+"']").prop('selected',true);        
            }
        });
      }
     
    </script>
@stop