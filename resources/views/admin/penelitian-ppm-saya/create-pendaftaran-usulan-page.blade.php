@extends('adminlte::page')

@section('title', 'Pendaftaran Usulan')

@section('content_header')
    <h1>Pendaftaran Usulan {{$jenis_usulan->jenis_usulan}}</h1>
    <ol class="breadcrumb">
      <li> <a href="{{url('pendaftaran-usulan')}}">Pendaftaran Usulan</a> </li>
      <li class="active"> Buat Usulan {{$jenis_usulan->jenis_usulan}}</li>
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
          <center><h3>Formulir Pendaftaran Usulan</h3></center>
        </div>
        <div class="box-body">
          
          <div class="row setup-content" id="step-1">
            <div class="col-lg-12">
              <form class="form-horizontal" id="form-usulan" enctype="multipart/form-data" method="post" action="{{url('pendaftaran-usulan/'.encrypt($jenis_usulan->id_jenis_usulan))}}">
                @csrf
                
                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                  <label class="col-lg-3 control-label">Pilih Pendanaan Penerimaan @include('wajib')</label>
                  <div class="col-lg-7 col-xs-12">
                    <select name="buka_penerimaan_id" class="form-control"  id="buka_penerimaan_id" required="">
                      <option value="">Pilih</option>
                      @foreach($buka_penerimaan as $b)
                      <option value="{{$b->id_buka_penerimaan}}">{{$b->sumber_dana->nama_sumber_dana.' - '.$b->unit_kerja->nama_unit.' - '.$b->skim->nama_skim}}</option>
                      @endforeach
                    </select>
                    <span class="message"> </span>
                  </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                  <label class="col-lg-3 control-label">Bidang {{$jenis_usulan->jenis_usulan}} @include('wajib')</label>
                  <div class="col-lg-7 col-xs-12">
                    <select name="bidang_id" class="form-control"  id="bidang_id" required="" onchange="load_tema()">
                      <option value="">Pilih Bidang</option>
                      @foreach($bidang as $b)
                      <option value="{{$b->id_bidang}}">{{$b->nama_bidang}}</option>
                      @endforeach
                    </select>
                    <span class="message"> </span>
                  </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                  <label class="col-lg-3 control-label">Tema {{$jenis_usulan->jenis_usulan}} @include('wajib')</label>
                  <div class="col-lg-7 col-xs-12">
                    <select name="tema_id" class="form-control"  id="tema_id" required="">
                      <option value="">Pilih Tema</option>
                      
                    </select>
                    <span class="message"> </span>
                  </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                  <label class="col-lg-3 control-label">Judul {{$jenis_usulan->jenis_usulan}} @include('wajib')</label>
                  <div class="col-lg-7 col-xs-12" >
                    <textarea class="form-control" required="" name="judul" rows="5"></textarea>
                  </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                  <label class="col-lg-3 control-label">Ketua Pengusul </label>
                  <div class="col-lg-7 col-xs-12" style="padding-top: 5px;">
                    <b><u>{{Helpers::nama_gelar(auth()->user()->pegawai)}}</u><br>{{auth()->user()->pegawai->nip}}</b>
                  </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                  <label class="col-lg-3 control-label">ID Sinta Ketua </label>
                  <div class="col-lg-7 col-xs-12" >
                    <input type="text" class="form-control" name="id_sinta_ketua" value="" placeholder="ID Sinta Ketua Pengusul">
                  </div>
                </div>
                
                
                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                  <label class="col-lg-3 control-label">Jenis Skema {{$jenis_usulan->jenis_usulan}} @include('wajib')</label>
                  <div class="col-lg-7 col-xs-12">
                    <select name="jenis_skema_id" class="form-control"  id="jenis_skema_id" required="" onchange="load_luaran_tkt()">
                      <option value="">Pilih Jenis Skema</option>
                      @foreach($jenis_usulan->jenis_skema as $js)
                      <option value="{{$js->id_jenis_skema}}">{{$js->nama_jenis_skema}}</option>
                      @endforeach
                    </select>
                    <span class="message"> </span>
                  </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                  <label class="col-lg-3 control-label">Tingkat Kesiapan Teknologi  @include('wajib')</label>
                  <div class="col-lg-7 col-xs-12">
                    <select name="tkt_id" class="form-control"  id="tkt_id" required="" >
                      <option value="">Pilih TKT</option>
                      
                    </select>
                    <span class="message"> </span>
                  </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                  <label class="col-lg-3 control-label">Luaran Wajib  @include('wajib')</label>
                  <div class="col-lg-7 col-xs-12">
                    <select name="luaran_wajib_id[]" class="form-control select2"  id="luaran_wajib_id" required="" multiple="" >
                      <option value="">Pilih Luaran Wajib</option>
                      
                    </select>
                    <small>Wajib isi, Bisa pilih lebih dari 1</small><br>
                    <span class="message"> </span>
                  </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                  <label class="col-lg-3 control-label">Luaran Tambahan  @include('wajib')</label>
                  <div class="col-lg-7 col-xs-12">
                    <select name="luaran_tambahan_id[]" class="form-control select2"  id="luaran_tambahan_id"  multiple="" >
                      <option value="">Pilih Luaran Tambahan</option>
                      
                    </select>
                    <small>Tidak wajib isi, Bisa pilih lebih dari 1</small><br>
                    <span class="message"> </span>
                  </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                  <label class="col-lg-3 control-label">Tanggal Kegiatan  @include('wajib')</label>
                  <div class="col-lg-7 col-xs-12">
                    <div class="col-lg-5" style="margin-left: -15px;">
                      <input type="text" name="tanggal_mulai" required=""   class="form-control datepicker"  placeholder="Tanggal Mulai" >
                    </div>
                    <div class="col-lg-2">
                      <b style="padding-top: 5px;">Sampai dengan</b>
                    </div>
                    <div class="col-lg-5" style="margin-left: -15px;">
                      <input type="text" name="tanggal_selesai" required=""  class="form-control datepicker"  placeholder="Tanggal Selesai" placeholder="Tahun">
                    </div>
                    
                    <span class="message"> </span>
                  </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                  <label class="col-lg-3 control-label">Jumlah Anggota  @include('wajib')</label>
                  <div class="col-lg-7 col-xs-12">
                    <select name="jumlah_anggota" class="form-control"  id="jumlah_anggota" required="">
                      <option value="">Pilih Jumlah Anggota</option>
                      @php
                      if ($jenis_usulan->id_jenis_usulan==1) $setup=Helpers::setup()->max_anggota_penelitian;
                      else  $setup=Helpers::setup()->max_anggota_pengabdian;
                       
                      
                      @endphp
                      @for($i=1; $i<=$setup ; $i++)
                      <option value="{{$i}}">{{$i.' orang'}}</option>
                      @endfor
                    </select>
                    <span class="message"> </span>
                  </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                  <label class="col-lg-3 control-label">Lokasi Kegiatan  @include('wajib')</label>
                  <div class="col-lg-7 col-xs-12">
                    <div class="pull-right">
                      <small><i>Tulis sebagian nama kecamatan, <b>Ingat!</b> hanya nama kecamatan</i></small>
                    </div>
                    <select name="wilayah_id" class="form-control select2ajax"  id="wilayah_id" required="">
                      
                    </select>
                    
                    <span class="message"> </span>
                  </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                  
                  <div class="col-lg-offset-3 col-lg-7 col-xs-12" >
                    <div class="pull-right">
                      <small><i>Tulis nama Desa/Kelurahan lokasi kegiatan, <b>Contoh: Desa Mendalo Darat</b></i></small>
                    </div>
                    <input type="text" class="form-control" name="nama_desa" value="" placeholder="Desa/Kelurahan Lokasi Kegiatan">
                  </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                  <label class="col-lg-3 control-label">File Proposal </label>
                  <div class="col-lg-7 col-xs-12" >
                    <div class="pull-right">
                      <small><i>Format file <b>PDF</b>, maksimal ukuran <b>5 MB</b></i></small>
                    </div>
                    <input type="file" class="form-control" required="" name="file_proposal" accept="application/pdf">
                    <span class="message"> </span>
                  </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                  <label class="col-lg-3 control-label">File Proposal Hasil Uji Turnitin</label>
                  <div class="col-lg-7 col-xs-12" >
                    <div class="pull-right">
                      <small><i>Format file <b>PDF</b>, maksimal ukuran <b>5 MB</b></i></small>
                    </div>
                    <input type="file" class="form-control" required="" name="file_proposal_turnitin" accept="application/pdf">
                    <span class="message"> </span>
                  </div>
                </div>
              </form>
            </div>

          </div>
        </div>
        <div class="box-footer">
          <div class="text-center">
            <button class="btn btn-primary btn-lg" onclick="konfirmasi_simpan()">Simpan Usulan <i class="fa fa-arrow-right"></i></button>
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
      $(".select2").select2();

      function konfirmasi_simpan()
      {
        alertify.confirm("Konfirmasi!","Apakah anda yakin sudah mengisi data dengan benar?",function(){
          $("#overlay").fadeIn(300);  
          $('#form-usulan').submit();
        },function(){

        });
       

      }
      function load_tema() {
        var id=$("#bidang_id").val();
        
        if (id!=='') {
          $.ajax({

            url:'{{url('load-tema')}}/'+id,
            type:'get',
            beforeSend:function()
            {
             $("#overlay").fadeIn(300);  
             },
             success:function(data)
             {
              $('#tema_id').html(data);
              $("#overlay").fadeOut(300);
             }
          });
        }
      }
      function load_luaran_tkt() {
        var id=$("#jenis_skema_id").val();
        
        if (id!=='') {
          $.ajax({

            url:'{{url('load-luaran-tkt')}}/'+id,
            type:'get',
            beforeSend:function()
            {
             $("#overlay").fadeIn(300);  
             },
             success:function(data)
             {
              $('#tkt_id').html(data.tkt);
              $('#luaran_wajib_id').html(data.luaran_wajib);
              $('#luaran_tambahan_id').html(data.luaran_tambahan);
              $("#overlay").fadeOut(300);
             }
          });
        }
      }
      
      
  $.validator.addMethod('filesize', function (value, element, param) {
    
  return this.optional(element) || (element.files[0].size <= param)
  });
  $.validator.addMethod('extensi', function (value, element, param) {
    
    return this.optional(element) || (element.files[0].type <= param)
  });
  
  

 $("#form-usulan").validate({
  focusInvalid: false,
    invalidHandler: function(form, validator) {
       $("#overlay").fadeOut(300);
        if (!validator.numberOfInvalids())

            return;

        $('html, body').animate({
            scrollTop: $(validator.errorList[0].element).offset().top
        }, 1000);


    },
  errorPlacement: function(label, elem) {
   
    elem.closest(".form-group").find(".message").append(label);
    elem.closest(".form-group").find(".message").append('<br>');

  },
   highlight: function(element, errorClass, validClass) {
    $(element).parents("div.form-group").addClass('has-error');
  },
  unhighlight: function(element, errorClass, validClass) {
    $(element).parents("div.form-group").removeClass('has-error');
  },
  focusCleanup: true,
  rules: {
    
     
    email: {
      required: true,
      email: true
    },
    file_proposal : {
      
      filesize : 5012000, // 5MB
    },
    file_proposal_turnitin : {
      
      filesize : 5012000, // 5MB
    }
    


  },
  messages: {
    required:"Wajib diisi!",
    tahun_mulai:{
      minlength:"Minimal 4 karakter",
      maxlength:"Maksiml 4 karakter"
    },
    file_proposal : {
      
      filesize : 'Maksimal Ukuran file 5 MB',
      accept:'Format file harus PDF',
    },
    file_proposal_turnitin : {
      
      filesize : 'Maksimal Ukuran file 5 MB',
      accept:'Format file harus PDF',
    },
    

  }
});
 $("#wilayah_id").select2({
    placeholder:"Tulis sebagian nama kecamatan lokasi kegiatan",
    allowClear:true,
    ajax:{
        url:"{{url('load-kecamatan')}}",
        dataTyper:"json",
        data:function(param)
        {
            var value= {
                search:param.term,
            };
            return value;
        },
        processResults:function(hasil)
        {
            return {
                results:hasil,
            };
        }
    },


  });

jQuery.extend(jQuery.validator.messages, {
  required: "Input harus diisi!",
});
      
    </script>
@stop