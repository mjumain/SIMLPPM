@extends('adminlte::page')

@section('title', 'Konfirmasi')

@section('content_header')
    <h1>Konfirmasi</h1>
    <ol class="breadcrumb">
      <li class="active"><i class="fa fa-address-book-o "></i> Konfirmasi</a></li>
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
          Konfirmasi Tawaran Kegiatan
        </div>
        <div class="box-body">
          <table class="table table-striped table-bordered" id='table'>
            <thead>
              <tr>
                <th width="2%">No</th>
                <th  width="7%">Tahun</th>
                <th  width="8%">Jenis</th>
                <th >Judul</th>
                <th>Ketua Pengusul</th>
                <th>Biaya Kegiatan</th>
                <th>Status Usulan</th>
                <th width="20%">#</th>
              </tr>
            </thead>
            <tbody>
              @foreach($tawaran as $data)
              <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$data->usulan->buka_penerimaan->tahun_anggaran->tahun }}</td>
                <td>{{$data->usulan->buka_penerimaan->jenis_usulan->jenis_usulan }}</td>
                <td><a href="{{'pendaftaran-usulan/detail/'.encrypt($data->usulan->id_usulan)}}"><u>{{$data->usulan->judul}}</u></a></td>
                <td><u><a href="{{url('data-umum/'.encrypt($data->usulan->ketua->pegawai->id_pegawai))}}">{{Helpers::nama_gelar($data->usulan->ketua->pegawai)}}<br>{{$data->usulan->ketua->pegawai->nip}}</a></u></td>
                <td>{{Uang::format_uang($data->usulan->dana_perjudul)}}</td>
                <td>{!!Helpers::status_usulan($data->usulan->status) !!}</td>
                <td>
                  @if($data->konfirmasi=='menunggu')
                  <button onclick="konfirmasi('{{$data->id_pelaksana}}','bersedia')" class="btn btn-primary btn-sm"><i class="fa fa-check"></i>Bersedia</button> | <button onclick="konfirmasi('{{$data->id_pelaksana}}','menolak')" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i>Menolak</button> 
                  @else
                    <div class="alert {{$data->konfirmasi=='bersedia'? 'alert-success':'alert-danger'}}">
                      <p>Sudah konfirmasi {{strtoupper($data->konfirmasi)}} pada tanggal {!!Tanggal::time_indo($data->tgl_konfirmasi)!!}</p>
                    </div>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          
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
 <script type="text/javascript">
   function konfirmasi(id,konfirmasi)
      {
        alertify.confirm("Konfirmasi","Apakah anda yakin konfirmasi <b>"+konfirmasi.toUpperCase()+"</b>?",
        function(){
          
          window.location.href="{{url('konfirmasi')}}/"+id+"/"+konfirmasi;
          
          
        },
        function(){
          alertify.error("Batal Menyimpan");
        });
        
    }
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
 </script>
    
@stop