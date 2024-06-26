@extends('adminlte::page')

@section('title', 'Tambah Penerimaan')

@section('content_header')
    <h1>Tambah Penerimaan</h1>
    <ol class="breadcrumb">
      <li ><a href="#"><i class="fa fa-wrench"></i> Setup Aplikasi</a></li>
      <li><a href="{{url('buka-penerimaan')}}"> Buka Penerimaan</a></li>
      <li class="active"> Tambah</li>
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
          Tambah Penerimaan
        </div>
        <div class="box-body">
          
          <form action="{{url('buka-penerimaan')}}" method="post" id="form-buka-penerimaan">
            @csrf
            <div class="row">
              <div class="form-group col-lg-3 col-md-3 col-xs-12">
                <label>Jenis Usulan @include('wajib')</label>
                <select class="form-control" name="jenis_usulan_id" required="">
                  <option value="">Pilih jenis usulan</option>
                  @foreach($jenis_usulan as $t)
                  <option value="{{$t->id_jenis_usulan}}" >{{$t->jenis_usulan}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-lg-3 col-md-3 col-xs-12">
                <label>Tahun Penerimaan @include('wajib')</label>
                <select class="form-control" name="tahun_anggaran_id" required="">
                  <option value="">Pilih tahun</option>
                  @foreach($ta as $t)
                  <option value="{{$t->id_tahun_anggaran}}" >{{$t->tahun}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-lg-3 col-md-3 col-xs-12">
                <label>Sumber Dana @include('wajib')</label>
                <select class="form-control " name="sumber_dana_id" required="" >
                  <option value="">Pilih sumber dana</option>
                  @foreach($sumber_dana as $t)
                  <option value="{{$t->id_sumber_dana}}">{{$t->nama_sumber_dana}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-lg-3 col-md-3 col-xs-12">
                <label>Unit Kerja @include('wajib')</label>
                <select class="form-control" name="unit_kerja_id" required="">
                  <option value="">Pilih unit kerja</option>
                  @foreach($unit_kerja as $t)
                  <option value="{{$t->id_unit_kerja}}">{{$t->nama_unit}}</option>
                  @endforeach
                </select>
              </div>
              
            </div>
            <div class="row">
              <div class="form-group col-lg-3 col-md-3 col-xs-12">
                <label>Skim @include('wajib')</label>

                <select class="form-control select2" name="skim_id" required="" style="width: 100%">
                  <option value="">Pilih skim</option>
                  @foreach($skim as $t)
                  <option value="{{$t->id_skim}}">{{$t->nama_skim}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-lg-3 col-md-3 col-xs-12">
                <label>Jumlah Dana (hanya angka)@include('wajib')</label>
                
                <input type="text"  name="jumlah_dana" class="form-control" required="" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Masukan Jumlah Dana" >
                <small id="terbilang"></small>
                
              </div>
              <div class="form-group col-lg-3 col-md-3 col-xs-12">
                <label>Jumlah Judul (hanya angka)@include('wajib')</label>
                <input type="text"  name="jumlah_judul" class="form-control" required="" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Masukan Jumlah Judul">  
              </div>
              <div class="form-group col-lg-3 col-md-3 col-xs-12">
                <label>Nomor SK @include('wajib')</label>
                <input type="text"  name="no_sk" class="form-control" required="" placeholder="Masukan Nomor SK">  
              </div>
              
            </div>
            <div class="row">
              <div class="form-group col-lg-3 col-md-3 col-xs-12">
                <label>Tanggal SK @include('wajib')</label>
                <input type="text"  name="tgl_sk" class="form-control datepicker" required="" placeholder="Masukan Tanggal SK">  
              </div>
              <div class="form-group col-lg-3 col-md-3 col-xs-12">
                <label>Jadwal Buka @include('wajib')</label>
                <input type="text"  name="jadwal_buka" class="form-control datepicker" required="" placeholder="Masukan Jadwal Buka">  
              </div>
              <div class="form-group col-lg-3 col-md-3 col-xs-12">
                <label>Jadwal Tutup @include('wajib')</label>
                <input type="text"  name="jadwal_tutup" class="form-control datepicker" required="" placeholder="Masukan Jadwal Tutup">  
              </div>
              <div class="form-group col-lg-3 col-md-3 col-xs-12">
                <label>Batas Upload Laporan Kemajuan </label>
                <input type="text"  name="batas_upload_laporan_kemajuan" class="form-control datepicker" placeholder="Masukan Batas Upload Laporan Kemajuan">  
              </div>
              
            </div>
            <div class="row">
              
              <div class="form-group col-lg-3 col-md-3 col-xs-12">
                <label>Batas Upload Laporan Akhir </label>
                <input type="text"  name="batas_upload_laporan_akhir" class="form-control datepicker"  placeholder="Masukan Batas Upload Laporan Akhir">  
              </div>
              <div class="form-group col-lg-3 col-md-3 col-xs-12">
                <label>Batas Upload Artikel </label>
                <input type="text"  name="batas_upload_artikel" class="form-control datepicker"  placeholder="Masukan Batas Upload Artikel">  
              </div>
              <div class="form-group col-lg-3 col-md-3 col-xs-12">
                <label>Batas Upload Luaran </label>
                <input type="text"  name="batas_upload_luaran" class="form-control datepicker"  placeholder="Masukan Batas Upload Luaran">  
              </div>
            </div>
            <div class="row">
              <div class="form-group col-lg-12 col-md-12 col-xs-12">
                <label></label>
                <button class="btn btn-sm btn-primary"> <i class="fa fa-floppy-o"></i> Simpan</button>
                
              </div>
            </div>

            
          </form>
              
         
          
        </div>
        <div class="box-footer">
          <p>Keterangan : @include('wajib') Wajib diisi!</p>
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
  @include('plugins.terbilang-js')

    <script>
      $(".select2").select2({

        placeholder:'Pilih data',
        allowClear: true
      });
      
      $("input[name='jumlah_dana']").keyup(function(e){
        e.preventDefault();
        $("#terbilang").html(terbilang($(this).val()));
      });

      $("#form-buka-penerimaan").submit(function($e){
        e.preventDefault();
        alertify.confirm("Konfirmasi!","Apakah sudah yakin data sudah diisi dengan benar semua ?",function(){

        },function
        (){

        })
      });
      

     
      
      
    </script>
@stop