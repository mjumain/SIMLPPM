@extends('adminlte::page')

@section('title', 'Data Diri')

@section('content_header')
    <h1>Data Diri</h1>
    <ol class="breadcrumb">
      <li class="active"><i class="fa fa-address-card"></i> Data Diri</a></li>
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
          Data Diri
          @if(auth()->user()->id_peg==$data->id_pegawai)
          <div class="pull-right">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-pendukung"><i class="fa fa-edit"></i> Lengkapi Data Pendukung</button>
          </div>
          @endif
        </div>
        <div class="box-body">
          <div class="alert alert-info">
            <p>Untuk Data Pegawai Silahkan Lengkapi di https://simpeg.umjambi.ac.id</p>
          </div>
          @include('layouts.data-diri-incl')
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-pendukung">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="{{url('data-diri/'.$data->id_pegawai)}}" method="post" class="form-horizontal" enctype="multipart/form-data" id='form-data-pendukung'>
                @csrf
                @method('patch')
                <div class="modal-header">
                  
                    <div class="modal-title">
                        <h3><b><i class="glyphicon glyphicon-plus"></i> Lengkapi Data Pendukung</b></h3>
                    </div>

                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Nomor Rekening</label>
                        <div class="col-sm-8">
                            <input type="text" name="no_rek" class="form-control" value="{{$data->data_pendukung ? $data->data_pendukung->no_rek:''}}" placeholder="Nomor Rekening" required="">
                            <small class="message"></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Nama Direkening</label>
                        <div class="col-sm-8">
                            <input type="text" name="nama_direkening" class="form-control" placeholder="Nama direkening" value="{{$data->data_pendukung ? $data->data_pendukung->nama_direkening:''}}" required="">
                            <small class="message"></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Bank</label>
                        <div class="col-sm-8">
                            @php
                            $data->data_pendukung ? $bank=$data->data_pendukung->bank_id:$bank="";
                            $data->data_pendukung ? $scan_rekening=$data->data_pendukung->scan_rekening:$scan_rekening=null;
                            $data->data_pendukung ? $scan_npwp=$data->data_pendukung->scan_rekening:$scan_npwp=null;
                            @endphp
                            <select class="form-control" name="bank_id" required="">
                              <option value="">Pilih bank yang digunakan</option>
                              @foreach(App\Bank::where('aktif','1')->get() as $b)
                              <option value="{{$b->id_bank}}" {{$bank==$b->id_bank ?'selected':'' }}>{{$b->nama_bank}}</option>
                              @endforeach
                            </select>
                            <small class="message"></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Nomor NPWP</label>
                        <div class="col-sm-8">
                            <input type="text" name="no_npwp" required="" class="form-control" placeholder="Nomor NPWP" value="{{$data->data_pendukung ? $data->data_pendukung->no_npwp:''}}" >
                        </div>
                        <small class="message"></small>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Scan Rekening</label>
                        <div class="col-sm-8">
                            <input type="file" name="scan_rekening" class="form-control"  {{isset($scan_rekening) ? '':'required'}}  accept="application/pdf">
                            <small>Format .pdf maks 500 Kb</small>
                            <small class="message"></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Scan NPWP</label>
                        <div class="col-sm-8">
                            <input type="file" name="scan_npwp" class="form-control"  {{isset($scan_npwp) ? '':'required'}} accept="application/pdf">
                            <small>Format .pdf maks 500 Kb</small>
                            <small class="message"></small>
                        </div>
                    </div>

                    
                    
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Close</button>
                    <button  class="btn btn-sm btn-info"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button>
                </div>
            </form>
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
 
    <script>

    
      $.validator.addMethod('filesize', function (value, element, param) {
    
        return this.optional(element) || (element.files[0].size <= param)
      });
      $.validator.addMethod('extensi', function (value, element, param) {
        
        return this.optional(element) || (element.files[0].type <= param)
      });
       var ukuran=501200;
       $("#form-data-pendukung").validate({
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
          
          scan_rekening : {
            
            filesize : ukuran, 
          },
          scan_npwp : {
            
            filesize : ukuran, 
          },
        },
        messages: {
          required:"Wajib diisi!",
          
          scan_rekening : {
            
            filesize : 'Maksimal Ukuran file 500 KB',
            accept:'Format file harus pdf',
          },
          scan_npwp : {
            
            filesize : 'Maksimal Ukuran file 500 KB',
            accept:'Format file harus pdf',
          },
          

        }
      });

      jQuery.extend(jQuery.validator.messages, {
        required: "Input harus diisi!",
      });
    </script>
@stop