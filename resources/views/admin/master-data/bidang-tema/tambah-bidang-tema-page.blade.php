@extends('adminlte::page')

@section('title','Tambah Data Bidang Tema')
@section('content_header')
    <h1>Tambah Bidang dan Tema</h1>
    <ol class="breadcrumb">
      <li ><a href="#"><i class="fa fa-clone"></i> Master Data</a></li>
      
      <li class="active">Tambah Bidang dan Tema </li>
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
         <form class="form-horizontal" action="{{url('master-bidang-tema')}}" method="post" id="form-create">
            @csrf
            <input type="hidden" name="tahun_anggaran_id" value="{{$taaktif->id_tahun_anggaran}}">
            <div class="row">
              <div class="col-xs-12">

                @csrf
                <div class="form-group">
                  <label class="control-label col-xs-2">Jenis Usulan</label>
                  <div class="col-xs-6">
                    <select class="form-control" required=""  name="jenis_usulan_id">
                      <option value="">Pilih Jenis Usulan</option>
                      @foreach($jenis_usulan as $j)
                      <option value="{{$j->id_jenis_usulan}}" >{{$j->jenis_usulan}}</option>
                      @endforeach
                    </select>

                  </div>

                </div>
                <div class="form-group">
                  <label class="control-label col-xs-2">Bidang</label>
                  <div class="col-xs-6">
                    <input type="text" name="nama_bidang" class="form-control" value=""  placeholder="Nama Bidang " required="">

                  </div>

                </div>
                <div class="form-group">
                  <label class="control-label col-xs-2">Tema</label>
                  <div class="col-xs-6">
                    <input type="text" name="nama_tema[]" class="form-control"  placeholder="Nama Tema" required="">

                  </div>

                </div>
                <div class="tampil-form-tema"></div>
                <template id="template-tema">
                  <div id="form-tema">
                    <div class="form-group">
                      <label class="col-xs-2"></label>
                      <div class="col-xs-6 ">

                        <input type="text"  name="nama_tema[]" class="form-control" placeholder="Nama Tema">

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

          if ($("select[name='jenis_usulan_id']").val()==""||$("input[name='nama_bidang']").val()=="") {
            alertify.alert("Peringatan","input tidak boleh kosong");
          }else{

            $('#form-create').submit();
          }

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
      
    </script>
@stop