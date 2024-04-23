@extends('adminlte::page')

@section('title', 'Setup Tahun Penerimaan')

@section('content_header')
    <h1>Setup Tahun Penerimaan</h1>
    <ol class="breadcrumb">
      <li ><a href="#"><i class="fa fa-wrench"></i> Setup Aplikasi</a></li>
      <li class="active"><a href="{{url('/setup-tahun-penerimaan')}}"> Setup Tahun Penerimaan</a></li>
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
                  <th width="15%">Tahun </th>
                  <th>Tanggal Mulai</th>
                  <th>Tanggal Akhir</th>
                  <th>No DIPA</th>
                  <th>Tanggal DIPA</th>
                  <th>Status</th>
                  <th width="10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($datas as $data)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$data->tahun }}</td>
                  <td>{!!Tanggal::tgl_indo($data->tgl_mulai) !!}</td>
                  <td>
                     {!!Tanggal::tgl_indo($data->tgl_akhir) !!}
                  </td>
                  <td>
                     {!!$data->no_dipa!!}
                  </td>
                  <td>
                     {!!Tanggal::tgl_indo($data->tgl_dipa)!!}
                  </td>
                  <td>
                     {!!Helpers::label_aktif($data->status)!!}
                  </td>
                  <td>
                     <div class="dropdown ">
                        <button id="dLabel" class="btn btn-primary btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-angle-down"></i> Aksi
                      
                        </button>
                        <ul class=" dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                          <li>
                              <a onclick="aktifkan('{{$data->id_tahun_anggaran}}')"> Aktifkan</a>
                          </li>
                          <li>
                              <a onclick="edit('{{$data->id_tahun_anggaran}}')"> Edit</a>
                          </li>
                          <li>
                              <a onclick="hapus('{{$data->id_tahun_anggaran}}','{{$data->tahun}}')" > Delete</a>
                              <form action="{{url('setup-tahun-penerimaan/'.$data->id_tahun_anggaran)}}" method="post" id="{{$data->id_tahun_anggaran}}">
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

          <br>

          <h3>Pengaturan Aplikasi</h3><button data-toggle="modal" data-target="#modal-setup" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Ubah</button>
          <div class="table-responsive">
            <table class="table">
              <tr>
                <td width="20%">Maks. Jumlah Penelitian:</td>
                <td width="1%"></td>
                <td>{{$setup->max_jumlah_menjadi_ketua_penelitian." Ketua + ".$setup->max_jumlah_menjadi_anggota_penelitian." Anggota atau ".$setup->atau_max_jumlah_menjadi_anggota_penelitian." Menjadi anggota saja"}}</td>
              </tr> 
              <tr>
                <td width="20%">Maks. Jumlah Pengabdian:</td>
                <td width="1%"></td>
                <td>{{$setup->max_jumlah_menjadi_ketua_pengabdian." Ketua + ".$setup->max_jumlah_menjadi_anggota_pengabdian." Anggota atau ".$setup->atau_max_jumlah_menjadi_anggota_pengabdian." Menjadi anggota saja"}}</td>
              </tr> 
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
                        <label class="control-label col-sm-3">Tahun Anggaran</label>
                        <div class="col-sm-8">
                            <input type="text" name="tahun" maxlength="4" id="tahun" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Tahun Anggaran" required="">
                            <small>
                              contoh : 2020
                            </small>
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Tanggal Mulai</label>
                        <div class="col-sm-8">
                            <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control"  placeholder="Tanggal Mulai Kegiatan" required="">
                            
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Tanggal Akhir</label>
                        <div class="col-sm-8">
                            <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control"  placeholder="Tanggal Akhir Kegiatan" required="">
                            
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">No DIPA</label>
                        <div class="col-sm-8">
                            <input type="text" name="no_dipa" id="no_dipa" class="form-control"  placeholder="No DIPA" required="">
                            
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Tanggal DIPA</label>
                        <div class="col-sm-8">
                            <input type="date" name="tgl_dipa" id="tgl_dipa" class="form-control"  placeholder="Tanggal DIPA" required="">
                            
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Tangal Umum Hasil Review Proposal</label>
                        <div class="col-sm-8">
                            <input type="date" name="tgl_umum_hasil_review_proposal" id="tgl_umum_hasil_review_proposal" class="form-control"  placeholder="Tanggal Umum Hasil Review Proposal" required="">
                            
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Tangal Umum Hasil Review Evaluasi Hasil</label>
                        <div class="col-sm-8">
                            <input type="date" name="tgl_umum_hasil_review_evaluasi_hasil" id="tgl_umum_hasil_review_evaluasi_hasil" class="form-control"  placeholder="Tanggal Umum Hasil Review Evaluasi Hasil" required="">
                            
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
  <div class="modal fade" id="modal-setup">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{url('setup-aplikasi/1')}}" id="modal-form" method="post" class="form-horizontal">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <div class="modal-title">
                        <h3>Ubah Pengaturan Aplikasi</h3>
                    </div>
                </div>
                <div class="modal-body">
                    
                  <div class="form-group">
                    <label class="control-label col-sm-3">Maks. Jumlah Penelitian</label>
                    <div class="col-sm-2">
                      <label>Ketua</label>
                      <input type="text" name="max_jumlah_menjadi_ketua_penelitian" maxlength="2" id="max_jumlah_menjadi_ketua_penelitian" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Ketua" required="" value="{{$setup->max_jumlah_menjadi_ketua_penelitian}}">
                    </div>
                    <div class="col-sm-1">
                      +
                    </div>
                    <div class="col-sm-2">
                      <label>Anggota</label>
                      <input type="text" name="max_jumlah_menjadi_anggota_penelitian" maxlength="2" id="max_jumlah_menjadi_anggota_penelitian" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Anggota" required="" value="{{$setup->max_jumlah_menjadi_anggota_penelitian}}">
                    </div>
                    <div class="col-sm-1">
                      Atau
                    </div>
                    <div class="col-sm-2">
                      <label>Anggota</label>
                      <input type="text" name="atau_max_jumlah_menjadi_anggota_penelitian" maxlength="2" id="atau_max_jumlah_menjadi_anggota_penelitian" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Anggota" required="" value="{{$setup->atau_max_jumlah_menjadi_anggota_penelitian}}">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-sm-3">Maks. Jumlah Pengabdian</label>
                    <div class="col-sm-2">
                      <label>Ketua</label>
                      <input type="text" name="max_jumlah_menjadi_ketua_pengabdian" maxlength="2" id="max_jumlah_menjadi_ketua_pengabdian" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Ketua" required="" value="{{$setup->max_jumlah_menjadi_ketua_pengabdian}}">
                    </div>
                    <div class="col-sm-1">
                      +
                    </div>
                    <div class="col-sm-2">
                      <label>Anggota</label>
                      <input type="text" name="max_jumlah_menjadi_anggota_pengabdian" maxlength="2" id="max_jumlah_menjadi_anggota_pengabdian" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Anggota" required="" value="{{$setup->max_jumlah_menjadi_anggota_pengabdian}}">
                    </div>
                    <div class="col-sm-1">
                      Atau
                    </div>
                    <div class="col-sm-2">
                      <label>Anggota</label>
                      <input type="text" name="atau_max_jumlah_menjadi_anggota_pengabdian" maxlength="2" id="atau_max_jumlah_menjadi_anggota_pengabdian" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Anggota" required="" value="{{$setup->atau_max_jumlah_menjadi_anggota_pengabdian}}">
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
        var url=baseURL()+"/setup-tahun-penerimaan";
        $('#modal-form').attr('action',url);
        $('#judul').html("<i class='fa fa-plus'></i> Tambah Setup Tahun Penerimaan");
        $('#tahun').val("");
        $('#tgl_mulai').val("");
        $('#tgl_akhir').val("");
        
        $('#tgl_dipa').val("");
        
        $('#no_dipa').val("");
        $('#tgl_umum_hasil_review_proposal').val("");
        $('#tgl_umum_hasil_review_evaluasi_hasil').val("");
      }
      function hapus(id,tahun) {
        alertify.confirm("Konfirmasi!","Apakah anda yakin menghapus tahun penerimaan  "+tahun+" ?",function(){
          $('#'+id).submit();
        },function(){

        })
      }
      function edit(id)
      {
         $.ajax({
            url:baseURL()+"/setup-tahun-penerimaan/"+id+"/edit",
            type:'get',
            success:function(data)
            {
                $('#modal').modal('show');
                $('#method').html("<input type='hidden' name='_method' value='PUT'>");
                $('#judul').html("<i class='fa fa-edit'></i> Edit Tahun Penerimaan");
                var url=baseURL()+"/setup-tahun-penerimaan/"+id;
                $('#modal-form').attr('action',url);
                
                $('#tahun').val(data.tahun);
                $('#tgl_mulai').val(data.tgl_mulai);
                $('#tgl_akhir').val(data.tgl_akhir);
                
                $('#tgl_dipa').val(data.tgl_dipa);
                
                $('#no_dipa').val(data.no_dipa);
                $('#tgl_umum_hasil_review_proposal').val(data.tgl_umum_hasil_review_proposal);
                $('#tgl_umum_hasil_review_evaluasi_hasil').val(data.tgl_umum_hasil_review_evaluasi_hasil);
                        
            }
        });
      }
      function aktifkan(id) {
        alertify.confirm("Konfirmasi!","Apakah anda yakin mengaktifkan tahun anggaran ini ?",function(){
          window.location.href="{{url('setup-tahun-penerimaan/aktifkan')}}/"+id
        },function(){

        })
      }
      
    </script>
@stop