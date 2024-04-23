@extends('adminlte::page')

@section('title', 'Penelitian dan PPM Saya')

@section('content_header')
    <h1>Penelitian dan PPM Saya</h1>
    <ol class="breadcrumb">
      
      <li class="active"> Penelitian dan PPM Saya</li>
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
            <a href="{{url('/ekspor-excel-ppm-perdosen/'.encrypt(auth()->user()->id_peg))}}" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Excel</a>
          </div>
        </div>
        <div class="box-body">
          
          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="table">
              <thead>
                <tr>
                  <th width="2%" rowspan="2">No</th>
                  <th rowspan="2" width="5%">Tahun</th>
                  <th rowspan="2" width="15%">Jenis</th>
                  <th rowspan="2">Judul</th>
                  <th colspan="2">Pelaksana</th>
                  
                  <th rowspan="2" width="8%">Aksi</th>
                </tr>
                <tr>
                  <th>Nama</th>
                  <th>Peran</th>
                 
                </tr>
              </thead>
              <tbody>

                @forelse($usulan as $data)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$data->buka_penerimaan->tahun_anggaran->tahun}}</td>
                  <td>{{$data->buka_penerimaan->jenis_usulan->jenis_usulan.' - '.$data->buka_penerimaan->sumber_dana->nama_sumber_dana.' - '.$data->buka_penerimaan->unit_kerja->nama_unit.' - '.$data->buka_penerimaan->skim->nama_skim}}</td>
                  <td>{{$data->judul}}</td>
                  <td>
                    <table width="100%">
                      
                      @foreach($data->pelaksana as  $a)
                      <tr>
                        <td valign="top">{!! $loop->iteration !!}. </td>
                        <td>
                          @if($a->pegawai)
                          <u>{{Helpers::nama_gelar($a->pegawai)}}</u>
                          <br>
                          <a href="{{url('/data-umum/'.$a->pegawai->id_pegawai)}}">{{$a->pegawai->nip}}</a>
                          @else
                          Belum Isi Anggota
                          @endif

                        </td>

                         
                      </tr>
                      @endforeach
                      
                    </table>
                     
                  </td>

                  <td>
                     <table width="100%" >
                      
                      @foreach($data->pelaksana as  $a)
                      <tr>
                        
                        <td>
                          
                         {!!Helpers::jabatan($a->jabatan)!!}
                         <br>
                        </td>

                         
                      </tr>
                      @endforeach
                      
                    </table>
                  </td>
                  
                  <td>
                     <div class="dropdown ">
                        <button id="dLabel" class="btn btn-primary btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-angle-down"></i> Aksi
                      
                        </button>
                        <ul class=" dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                          
                          <li>
                              <a href="{{url('penelitian-ppm-saya/detail/'.encrypt($data->id_usulan))}}"> Detail Usulan</a>
                          </li>
                          @if($data->ketua->id_peg==auth()->user()->id_peg)
                          <li>
                              <a href="{{url('penelitian-ppm-saya/laporan/'.encrypt($data->id_usulan))}}"> Laporan</a>
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
      
      function hapus(id,skema) {
        alertify.confirm("Konfirmasi!","Apakah anda yakin menghapus jenis skema "+skema+" ?",function(){
          $('#'+id).submit();
        },function(){

        })
      }
      function ajukan(id,judul) {
        alertify.confirm("Konfirmasi!","Apakah anda yakin mengajukan kegiatan dengan judul  "+judul+" ?",function(){
          window.location.href="{{url('pendaftaran-usulan/ajukan')}}/"+id;
        },function(){

        })
      }
      
      
    </script>
@stop