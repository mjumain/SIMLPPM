@extends('adminlte::page')

@section('title', 'Edit Data Bidang Tema')

@section('content_header')
    <h1>Edit Data Bidang Tema</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-clone"></i> Master Data</a></li>
      <li><a href="{{url('/master-bidang-tema')}}"> Master Bidang dan Tema</a></li>
      <li class="active"><a> Edit</a></li>
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
          <form class="form-horizontal" action="{{url('master-bidang-tema/'.$data->id_bidang)}}" method="post" id="form-create">
            @csrf
            @method('PUT')
            <input type="hidden" name="tahun_anggaran_id" value="{{$data->tahun_anggaran_id}}">
            <div class="row">
              <div class="col-xs-12">

                @csrf
                <div class="form-group">
                  <label class="control-label col-xs-2">Jenis Usulan</label>
                  <div class="col-xs-6">
                    <select class="form-control" required=""  name="jenis_usulan_id">
                      <option value="">Pilih Jenis Usulan</option>
                      @foreach($jenis_usulan as $j)
                      <option value="{{$j->id_jenis_usulan}}" {{$data->jenis_usulan_id==$j->id_jenis_usulan ? 'selected':''}}>{{$j->jenis_usulan}}</option>
                      @endforeach
                    </select>

                  </div>

                </div>
                <div class="form-group">
                  <label class="control-label col-xs-2">Bidang</label>
                  <div class="col-xs-6">
                    <input type="text" name="nama_bidang" class="form-control"   placeholder="Nama Bidang " required="" value="{{$data->nama_bidang}}">

                  </div>

                </div>
                @foreach($data->tema as $no=> $tema)
                <div class="form-group">
                    <label class="control-label col-xs-2">Tema {{$no+1 }}</label>
                    <div class="col-xs-6 ">
                        <div class="input-group">
                            
                       
                            <input type="hidden" name="id_tema[]" value="{{$tema->id_tema}}">

                            <input type="text" name="nama_tema[]" value="{{$tema->nama_tema}}" class="form-control"  placeholder="Tema" required="">
                            <div class="input-group-btn">
                                <button type="button" onclick="hapustema('{{$tema->id_tema}}','{{$tema->nama_tema}}')" class="btn btn-danger ">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                </div>
                @endforeach
                <div class="tampil-form-tema"></div>
                <template id="template-tema">
                  <div id="form-tema">
                    <div class="form-group">
                      <label class="col-xs-2"></label>
                      <div class="col-xs-6 ">

                        <input type="text"  name="nama_tema_baru[]" class="form-control" placeholder="Nama Tema">

                      </div>
                    </div>
                  </div>
                </template>
                <div class="form-group">
                  <div class="col-xs-offset-2 col-lg-8">
                    <button type="button" onclick="konfirmasi_simpan()" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Simpan</button> | <button type="button" onclick="tambah_tema()" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-plus"></i> Tambah Form Tema</button> | <button type="button" onclick="hapus_tema()" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i> Hapus Form Tema</button>
                  </div>

                </div>


              </div>
            </div>
          </form>
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
  <script type="text/javascript" src="{{asset('js/mustache.js')}}"></script>
    <script>
    function konfirmasi_simpan()
    {
      alertify.confirm("Konfirmasi","Apakah anda yakin menyimpan data ini?",
        function(){
          $('#form-create').submit();

        },
        function(){
          alertify.error("Batal simpan");
        });

    }
    function tambah_tema()
    {
      var form= $("#template-tema").html();
      $(".tampil-form-tema").append(Mustache.render(form));
    }
    function hapus_tema()
    {
      $("#form-tema").remove();
    }
    function hapustema(id,tema)
    {
        alertify.confirm("Konfirmasi","Apakah anda yakin menghapus tema "+tema+" ?",
        function(){
            window.location.href="{{url('master-bidang-tema/hapus-tema')}}/"+id;
          
        },
        function(){
          alertify.error("Batal hapus");
        });
        
    }
    </script>
@stop