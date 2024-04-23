@extends('adminlte::page')

@section('title', 'Dashboard Dosen')

@section('content_header')

<h1>
  Dashboard Dosen
</h1>
<ol class="breadcrumb">
  <li class="active"><a href="#"><i class="fa fa-desktop"></i> Dashboard Dosen</a></li>
  
</ol>

@stop

@section('content')


  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>{{$tawaran}}</h3>

          <p>Tawaran Menjadi Anggota</p>
        </div>
        <div class="icon">
          <i class="fa fa-user"></i>
        </div>
        <a href="{{url('konfirmasi')}}" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{$usulan_diajukan}}</h3>

          <p>Usulan Sedang Diajukan</p>
        </div>
        <div class="icon">
          <i class="glyphicon glyphicon-new-window"></i>
        </div>
        <a href="{{url('pendaftaran-usulan')}}" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{$usulan_disetujui}}</h3>

          <p>Usulan Disetujui</p>
        </div>
        <div class="icon">
          <i class="glyphicon glyphicon-check"></i>
        </div>
        <a href="{{url('penelitian-ppm-saya')}}" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-red">
        <div class="inner">
          <h3>{{$usulan_ditolak}}</h3>

          <p>Usulan Ditolak</p>
        </div>
        <div class="icon">
          <i class="glyphicon glyphicon-remove"></i>
        </div>
        <a href="{{url('pendaftaran-usulan')}}" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
  </div>
  @if($usulan_revisi >0)
  <div class="row">
    <div class="col-lg-12">
      <div class="alert alert-danger">
        Ada <b>{{$usulan_revisi}}</b> yang sedang diajukan tahun ini harus <b>DIREVISI</b> !, <a href="{{url('pendaftaran-usulan')}}" class="small-box-footer">Klik disini unutk lebih lengkapnya <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>
  @endif
  <div class="row">
    <div class="col-lg-12 col-xs-12">
      
      <div class="box box-info">
        <div class="box-header with-border">
          <b> Penerimaan Usulan  Sedang Berjalan</b>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="table">
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
                  <th>Jadwal Upload Laporan</th>
                </tr>
              </thead>
              <tbody>
                @forelse($buka_penerimaan as $data)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$data->jenis_usulan->jenis_usulan }}</td>
                  <td>{{$data->sumber_dana->nama_sumber_dana }}</td>
                  <td>{{$data->unit_kerja->nama_unit }}</td>
                  <td>{{$data->skim->nama_skim }}</td>
                  <td>{{Uang::format_uang($data->jumlah_dana)}}</td>
                  <td>{{$data->jumlah_judul }}</td>
                  <td>
                    
                    {!!Tanggal::tgl_indo($data->jadwal_buka).' s/d '.Tanggal::tgl_indo($data->jadwal_tutup) !!}<br> 
                    {!!Helpers::status_penerimaan($data)!!}
                  </td>
                  <td>
                    <table width="100%">
                      
                      <tr valign="top">
                        <td>Lap. Kemajuan</td><td width="2%">:</td><td>&nbsp;{!!Tanggal::tgl_indo($data->batas_upload_laporan_kemajuan)!!}</td>
                      </tr>
                      
                      <tr valign="top">
                        <td>Lap. Akhir</td><td width="2%">:</td><td>&nbsp; {!!Tanggal::tgl_indo($data->batas_upload_laporan_akhir)!!}</td>
                      </tr>
                      <tr valign="top">
                        <td>Artikel</td><td width="2%">:</td><td>&nbsp;{!!Tanggal::tgl_indo($data->batas_upload_artikel)!!}</td>
                      </tr>
                      <tr valign="top">
                        <td>Luaran</td><td width="2%">:</td><td>&nbsp;{!!Tanggal::tgl_indo($data->batas_upload_luaran)!!}</td>
                      </tr>
                    </table>
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

@include('plugins.datepicker-css')
@include('plugins.highchart-css')
<style type="text/css">
  .icon{
    top:10px !important;
  }
</style>
@stop

@section('js')


<script type="text/javascript">
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
</script>
@stop