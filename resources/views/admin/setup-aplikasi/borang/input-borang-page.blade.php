@extends('adminlte::page')

@section('title', 'Borang '.$judul_tahap.' '.$jenis_skema->nama_jenis_skema)

@section('content_header')
    <h1>{{'Borang '.$judul_tahap.' '.$jenis_skema->nama_jenis_skema}}</h1>
    <ol class="breadcrumb">
      <li ><a href="#"><i class="fa fa-wrench"></i> Setup Aplikasi</a></li>
      <li ><a href="{{url('/setup-borang')}}"> Setup Borang</a></li>
      <li class="active">{{'Borang '.$judul_tahap.' '.$jenis_skema->nama_jenis_skema}} </li>
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
            <a href="{{url('setup-borang/input-borang/'.$tahap.'/'.$jenis_skema->id_jenis_skema.'/create')}}"  class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah</a>
          </div>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="table">
              <thead>
                <tr>
                  <th width="2%">No</th>
                  <th width="50%">Komponen Penilaian</th>
                  <th width="10%">Bobot (%)</th>
                  <th>Opsi Skor</th>
                  <th width="10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($borang as $data)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{!! $data->komponen_penilaian!!}</td>
                  <td>[ {{$data->bobot.' %' }} ]</td>
                  <td>
                    <ul>
                     @foreach($data->skor_borang as $nilai)
                     <li>{{$nilai->skor.'  '.$nilai->keterangan}}</li>
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
                              <a href="{{url('setup-borang/input-borang/'.$tahap.'/'.$jenis_skema->id_jenis_skema.'/'.$data->id_borang.'/edit')}}"> Edit Komponen Borang</a>
                          </li>
                          <li>
                              <a onclick="hapus('{{$data->id_borang}}')" > Delete</a>
                              <form action="{{url('setup-borang/input-borang/'.$tahap.'/'.$jenis_skema->id_jenis_skema.'/'.$data->id_borang)}}" method="post" id="borang{{$data->id_borang}}">
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
      
      function hapus(id) {
        alertify.confirm("Konfirmasi!","Apakah anda yakin menghapus komponen penilaian ini?",function(){
          $('#borang'+id).submit();
        },function(){

        })
      }
      
      
      
    </script>
@stop