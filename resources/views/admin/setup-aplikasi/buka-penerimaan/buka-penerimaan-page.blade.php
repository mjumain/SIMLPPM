@extends('adminlte::page')

@section('title', 'Buka Penerimaan')

@section('content_header')
    <h1>Buka Penerimaan</h1>
    <ol class="breadcrumb">
      <li ><a href="#"><i class="fa fa-wrench"></i> Setup Aplikasi</a></li>
      <li class="active"> Buka Penerimaan</li>
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
          Filter Pencarian
        </div>
        <div class="box-body">
          
          <form action="{{url('buka-penerimaan')}}" method="get">
          
            <div class="form-group col-lg-3 col-md-3 col-xs-12">
              <label>Tahun Penerimaan</label>
              <select class="form-control select2" name="tahun_anggaran_id" style="width: 100%">
                @foreach(App\TahunAnggaran::orderBy('tahun','desc')->get() as $t)
                <option value="{{$t->id_tahun_anggaran}}" {{$t->id_tahun_anggaran==$ta->id_tahun_anggaran ? 'selected':''}}>{{$t->tahun}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-lg-3 col-md-3 col-xs-12">
              <label>Sumber Dana</label>
              <select class="form-control select2" name="sumber_dana_id" style="width: 100%">
                <option value="">Semua</option>
                @foreach(App\SumberDana::get() as $t)
                <option value="{{$t->id_sumber_dana}}" {{$t->id_sumber_dana==$sumber ? 'selected':''}}>{{$t->nama_sumber_dana}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-lg-3 col-md-3 col-xs-12">
              <label>Unit Kerja</label>
              <select class="form-control select2" name="unit_kerja_id" style="width: 100%">
                <option value="">Semua</option>
                @foreach(App\UnitKerja::get() as $t)
                <option value="{{$t->id_unit_kerja}}" {{$t->id_unit_kerja==$unit_kerja ? 'selected':''}}>{{$t->nama_unit}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-lg-3 col-md-3 col-xs-12">
              <label>Skim</label>

              <select class="form-control select2" name="skim_id" style="width: 100%">
                <option value="">Semua</option>
                @foreach(App\Skim::get() as $t)
                <option value="{{$t->id_skim}}" {{$t->id_skim==$skim ? 'selected':''}}>{{$t->nama_skim}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-lg-3 col-md-3 col-xs-12">
              <button class="btn btn-sm btn-primary">Go</button>
              
            </div>
            
          </form>
              
         
          
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 col-xs-12">
     
      <div class="box box-info">
        <div class="box-header with-border">
          <div class="pull-right">
            <a href="{{url('buka-penerimaan/create')}}"  class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah</a>
          </div>
        </div>
        <div class="box-body">
          
          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="table-penelitian">
              <thead>
                <tr>
                  <th width="2%">No</th>
                  <th>Jenis Usulan</th>
                  <th>Sumber Dana</th>
                  <th>Unit Kerja</th>
                  <th>Skim</th>
                  <th>Jumalah Dana</th>
                  <th>Judul</th>
                  <th>Jadwal</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($datas as $data)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$data->jenis_usulan->jenis_usulan }}</td>
                  <td>{{$data->sumber_dana->nama_sumber_dana }}</td>
                  <td>{{$data->unit_kerja->nama_unit }}</td>
                  <td>{{$data->skim->nama_skim }}</td>
                  <td>{{Uang::format_uang($data->jumlah_dana)}}</td>
                  <td>{{$data->jumlah_judul }}</td>
                  <td>
                    <table width="100%">
                      <tr>
                        <td>Buka</td><td width="2%">:</td><td>{!!Tanggal::tgl_indo($data->jadwal_buka)!!}</td>
                      </tr>
                      <tr>
                        <td>Tutup</td><td width="2%">:</td><td>{!!Tanggal::tgl_indo($data->jadwal_tutup)!!}</td>
                      </tr>
                      <tr>
                        <td>Lap. Kemajuan</td><td width="2%">:</td><td>{!!Tanggal::tgl_indo($data->batas_upload_laporan_kemajuan)!!}</td>
                      </tr>
                      
                      <tr>
                        <td>Lap. Akhir</td><td width="2%">:</td><td>{!!Tanggal::tgl_indo($data->batas_upload_laporan_akhir)!!}</td>
                      </tr>
                      <tr>
                        <td>Artikel</td><td width="2%">:</td><td>{!!Tanggal::tgl_indo($data->batas_upload_artikel)!!}</td>
                      </tr>
                      <tr>
                        <td>Luaran</td><td width="2%">:</td><td>{!!Tanggal::tgl_indo($data->batas_upload_luaran)!!}</td>
                      </tr>
                    </table>
                  </td>
                  >
                  
                  <td>
                     <div class="dropdown ">
                        <button id="dLabel" class="btn btn-primary btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-angle-down"></i> Aksi
                      
                        </button>
                        <ul class=" dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                          
                          <li>
                              <a href="{{url('buka-penerimaan/'.$data->id_buka_penerimaan.'/edit')}}"> Edit</a>
                          </li>
                          <li>
                              <a onclick="hapus('{{$data->id_buka_penerimaan}}')" > Delete</a>
                              <form action="{{url('buka-penerimaan/'.$data->id_buka_penerimaan)}}" method="post" id="{{$data->id_buka_penerimaan}}">
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
  
  @stop

@section('css')
  @include('plugins.alertify-css')
  @include('plugins.icon-picker-css')
@stop

@section('js')
  @include('plugins.alertify-js')
  @include('plugins.icon-picker-js')
    <script>
      
       
       var table=$('.table').DataTable( {
         
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
        alertify.confirm("Konfirmasi!","Apakah anda yakin menghapus penerimaan ini ?",function(){
          $('#'+id).submit();
        },function(){

        })
      }
      
      
    </script>
@stop