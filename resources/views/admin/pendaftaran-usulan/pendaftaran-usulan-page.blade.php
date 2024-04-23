@extends('adminlte::page')

@section('title', 'Pendaftaran Usulan')

@section('content_header')
    <h1>Data Pendaftaran Usulan</h1>
    <ol class="breadcrumb">
      
      <li class="active"> Pendaftaran Usulan</li>
    </ol>
@stop

@section('content')
  <div class="row">
    <div class="col-lg-12 col-xs-12">
      @if(Session::has('alert'))
        @include('layouts.alert')
      @endif
      <div class="box box-info">
        
        <div class="box-body">
          <div class="alert alert-">
            <p>
              <b>Mohon diperhatikan!</b>
              <ul>
                <li>1. Pastikan semua data diisi dengan baik dan benar</li>
                <li>2. Maksimal usulan penelitian yg boleh diajukan adalah 
                  {{Helpers::setup()->max_jumlah_menjadi_ketua_penelitian}} sebagai ketua + {{Helpers::setup()->max_jumlah_menjadi_anggota_penelitian}} sebagai anggota atau hanya {{Helpers::setup()->atau_max_jumlah_menjadi_anggota_penelitian}}  usulan sebagai anggota saja (tidak ada menjadi ketua)
                </li>
                <li>2. Maksimal usulan pengabdian yg boleh diajukan adalah 
                  {{Helpers::setup()->max_jumlah_menjadi_ketua_pengabdian}} sebagai ketua + {{Helpers::setup()->max_jumlah_menjadi_anggota_pengabdian}} sebagai anggota atau hanya {{Helpers::setup()->atau_max_jumlah_menjadi_anggota_pengabdian}} usulan sebagai anggota saja (tidak ada menjadi ketua)
                </li>
                
              </ul>
            </p>
          </div>
          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="table">
              <thead>
                <tr>
                  <th width="2%" rowspan="2">No</th>
                  <th rowspan="2" width="7%">Tahun</th>
                  <th rowspan="2" width="8%">Jenis</th>
                  <th rowspan="2">Judul</th>
                  <th colspan="3">Pelaksana</th>
                  <th rowspan="2" width="10%">Status Usulan</th>
                  <th rowspan="2" width="10%">Aksi</th>
                </tr>
                <tr>
                  <th>Nama</th>
                  <th>Peran</th>
                  <th>Konfirm</th>
                </tr>
              </thead>
              <tbody>

                @forelse($usulan as $data)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$data->buka_penerimaan->tahun_anggaran->tahun}}</td>
                  <td>{{$data->buka_penerimaan->jenis_usulan->jenis_usulan}}</td>
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
                       <table width="100%" >
                      
                      @foreach($data->pelaksana as  $a)
                      <tr>
                        
                        <td>
                          
                         {!!Helpers::konfirmasi($a)!!}
                        </td>

                         
                      </tr>
                      @endforeach
                      
                    </table>
                     
                  </td>
                  <td>
                    {!!Helpers::status_usulan($data->status)!!}
                    @if ($data->waktu_ajukan_revisi_proposal!=null) 
                      <br>Direvisi pada {{Tanggal::time_indo($data->waktu_ajukan_revisi_proposal)}}
                    @endif
                  </td>
                  <td>
                     <div class="dropdown ">
                        <button id="dLabel" class="btn btn-primary btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-angle-down"></i> Aksi
                      
                        </button>
                        <ul class=" dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                          
                          <li>
                              <a href="{{url('pendaftaran-usulan/detail/'.encrypt($data->id_usulan))}}"> Detail Usulan</a>
                          </li>
                          @if(Helpers::do_something($data)==true)
                          <li>
                              <a href="{{url('pendaftaran-usulan/edit/'.encrypt($data->id_usulan))}}"> {{$data->status=='revisi' ? 'Revisi Usulan':'Edit Usulan'}}</a>
                          </li>
                          <li>
                              <a href="{{url('pendaftaran-usulan/input-anggota/'.encrypt($data->id_usulan))}}"> Input Anggota</a>
                          </li>
                          <li>
                              <a onclick="ajukan('{{$data->id_usulan}}','{{preg_replace('/\s\s+/','',$data->judul)}}')"> Ajukan Usulan</a>
                          </li>
                          <li>
                              <a onclick="hapus('{{$data->id_usulan}}')" > Hapus</a>
                              <form action="{{url('pendaftaran-usulan/delete/'.$data->id_usulan)}}" method="post" id="{{$data->id_usulan}}">
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
        <div class="box-footer ">
          @if($buat_usulan==true)
          <div class="dropup ">
            <button id="dLabel" class="btn btn-primary btn-lg" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-angle-up"></i> Buat Usulan Baru
          
            </button>
            <ul class=" dropdown-menu" aria-labelledby="dLabel">
              @foreach(App\JenisUsulan::get() as $j)
              <li>
                  <a href="{{url('pendaftaran-usulan/'.encrypt($j->id_jenis_usulan))}}">{{$j->jenis_usulan}}</a>
              </li>
              @endforeach
              
            </ul>
          </div>
          @else
          <div class="alert alert-info">
            <p>Maaf belum ada penerimaan usulan yg dibuka</p>
          </div>
          @endif
          
          
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