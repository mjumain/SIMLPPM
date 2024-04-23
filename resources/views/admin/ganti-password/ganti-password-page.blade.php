@extends('adminlte::page')

@section('title', 'Pengaturan Akun')

@section('content_header')
    <h1>Pengaturan Akun</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-cogs"></i>Pengaturan Akun</a></li>
      
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
      
        </div>
        <div class="box-body">
          <form class='form-horizontal' action="{{url('pengaturan-akun')}}" method="post">
            @csrf
            
            <div class='form-group'>
              <label class='control-label col-lg-3'>Nama Gelar</label>
              <div class='col-lg-2 col-xs-2'>
                <input type="text" name="gelar_depan" class="form-control" placeholder="Gelar Depan"  value="{{auth()->user()->gelar_depan}}">
                <small>Contoh: Dr. Drs.</small>
              </div>

              <div class='col-lg-2 col-xs-2'>
                <input type="text" disabled=""  class="form-control"  value="{{auth()->user()->pegawai->nama_lengkap}}">
              </div>
              <div class='col-lg-2 col-xs-2'>
                <input type="text" name="gelar_belakang" class="form-control" placeholder="Gelar Belakang"  value="{{auth()->user()->gelar_belakang}}">
                <small>Contoh: S.Pd., M.Pd.</small>
              </div>
            </div>
            <div class='form-group'>
              <label class='control-label col-lg-3'>Alamat Email</label>
              <div class='col-lg-6 col-xs-12'>
                <input type="email" name="email" class="form-control" placeholder="Alamat email" required="" value="{{auth()->user()->email}}">
              </div>
            </div>
            <div class='form-group'>
              <label class='control-label col-lg-3'>Password Lama</label>
              <div class='col-lg-6 col-xs-12'>
                <input type="password" name="password_lama" class="form-control" placeholder="Password Lama">
              </div>
            </div>
            <div class='form-group'>
              <label class='control-label col-lg-3'>Password Baru</label>
              <div class='col-lg-6 col-xs-12'>
                <input type="password" name="password_baru" class="form-control" placeholder="Password Baru">
              </div>
            </div>
            <div class='form-group'>
              <label class='control-label col-lg-3'>Konfirmasi Password Baru</label>
              <div class='col-lg-6 col-xs-12'>
                <input type="password" name="konfirm_password_baru" class="form-control" placeholder="Password Baru">
              </div>
            </div>
            
            <div class='form-group'>
              
              <div class='col-lg-offset-3 col-lg-11'>
                <button class='btn btn-primary btn-md' type="submit"> Simpan</button>
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
  @include('plugins.tinymce-js')
    <script>
     tinymce.init({
        selector: 'textarea#isi',
        height: 800,
        
        plugins: [
          'advlist autolink lists link image charmap print preview hr anchor pagebreak',
          'searchreplace wordcount visualblocks visualchars code fullscreen',
          'insertdatetime media nonbreaking save table contextmenu directionality',
          'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
        ],
        toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'print preview media | forecolor backcolor emoticons | codesample',
        image_advtab: true,
        images_upload_url: '{{url("file-manager-direct")}}',
        images_upload_handler : function(blobInfo, success, failure) {
          var xhr, formData;
          xhr = new XMLHttpRequest();
          xhr.withCredentials = false;
          xhr.open('POST', '{{url("file-manager-direct")}}');
          xhr.setRequestHeader("X-CSRF-Token","{{csrf_token()}}");
          xhr.onload = function() {
            var json;
            if (xhr.status != 200) {
              failure('HTTP Error: ' + xhr.status);
              return;
            }
            json = JSON.parse(xhr.responseText);
            //alert(json);
            success(json.location);
          };
          formData = new FormData();
          formData.append('file', blobInfo.blob(), blobInfo.filename());
          xhr.send(formData);
        },
    });
     
    </script>
@stop