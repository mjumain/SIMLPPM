@extends('adminlte::page')

@section('title', 'Data Bidang dan Tema Penelitian/Pengabdian')

@section('content_header')
    <h1>Data Bidang dan Tema Penelitian/Pengabdian</h1>
    <ol class="breadcrumb">
      <li ><a href="#"><i class="fa fa-clone"></i> Master Data</a></li>
      <li class="active"><a href="{{url('/master-bidang-tema')}}"> Master Bidang dan Tema Penelitian dan Pengabdian</a></li>
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
          @if(auth()->user()->cek_akses('create-bidang'))
          <div class="pull-right">
            <a href="{{url('master-bidang-tema/create')}}"  class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah</a>
          </div>
          @endif
        </div>
        <div class="box-body">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Penelitian</a></li>
              <li><a href="#tab_2" data-toggle="tab">Pengabdian</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered" id="table-penelitian">
                    <thead>
                      <tr>
                        <th width="2%">No</th>
                        <th width="25%">Bidang</th>
                        <th>Tema</th>
                        <th width="10%">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($bidang_penelitian as $data)
                      <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$data->nama_bidang }}</td>
                        <td>
                          <ul>
                            @foreach($data->tema as $t)
                            <li>{{$t->nama_tema}}</li>
                            @endforeach
                          </ul>

                        </td>
                        
                        <td>
                           <div class="dropdown ">
                              <button id="dLabel" class="btn btn-primary btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-angle-down"></i> Aksi
                            
                              </button>
                              <ul class=" dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                                @if(auth()->user()->cek_akses('update-bidang'))
                                <li>
                                    <a href="{{url('master-bidang-tema/'.$data->id_bidang.'/edit')}}"> Edit</a>
                                </li>
                                @endif
                                @if(auth()->user()->cek_akses('delete-bidang'))
                                <li>
                                    <a onclick="hapus('{{$data->id_bidang}}','{{$data->nama_bidang}}')" > Delete</a>
                                    <form action="{{url('master-bidang-tema/'.$data->id_bidang)}}" method="post" id="{{$data->id_bidang}}">
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
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered" id="table-pengabdian">
                    <thead>
                      <tr>
                        <th width="2%">No</th>
                        <th width="25%">Bidang</th>
                        <th>Tema</th>
                        <th width="10%">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($bidang_pengabdian as $data)
                      <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$data->nama_bidang }}</td>
                        <td>
                          <ul>
                            @foreach($data->tema as $t)
                            <li>{{$t->nama_tema}}</li>
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
                                    <a href="{{url('master-bidang-tema/'.$data->id_bidang.'/edit')}}"> Edit</a>
                                </li>
                                <li>
                                    <a onclick="hapus('{{$data->id_bidang}}','{{$data->nama_bidang}}')" > Delete</a>
                                    <form action="{{url('master-bidang-tema/'.$data->id_bidang)}}" method="post" id="{{$data->id_bidang}}">
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
              
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
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
      
      function hapus(id,bidang) {
        alertify.confirm("Konfirmasi!","Apakah anda yakin menghapus bidang "+bidang+" ?",function(){
          $('#'+id).submit();
        },function(){

        })
      }
      
      
    </script>
@stop