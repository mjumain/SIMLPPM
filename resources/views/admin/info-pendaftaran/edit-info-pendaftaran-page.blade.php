@extends('adminlte::page')

@section('title', 'Edit Artikel Info Pendaftaran')

@section('content_header')
    <h1>Edit Artikel Info Pendaftaran</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-clone"></i>Artikel</a></li>
      <li ><a href="{{url('info-pendaftaran')}}"> Info Pendaftaran</a></li>
      <li class="active"><a href="{{url('info-pendaftaran/'.$data->id_info_pendaftaran.'/edit')}}">Edit Info Pendaftaran</a></li>
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
          <form class='form-horizontal' action="{{url('info-pendaftaran/'.$data->id_info_pendaftaran)}}" method="post">
            @csrf
            @method('PUT')
            <div class='form-group'>
              <label class='control-label col-lg-1'>Judul</label>
              <div class='col-lg-11'>
                <input type="text" name="judul" class="form-control" placeholder="Judul Artikel Info Pendaftaran" value="{{$data->judul}}">
              </div>
            </div>
            <div class='form-group'>
              <label class='control-label col-lg-1'>Isi</label>
              <div class='col-lg-11'>
                <textarea class='form-control' id='isi' name='isi' placeholder="Tulis isi artikel info pendaftaran" rows="25">{!!$data->isi!!}</textarea>
              </div>
            </div>
            <div class='form-group'>
              <label class='control-label col-lg-1'>Status</label>
              <div class='col-lg-11'>
                <div class='radio'>
                  <input type="radio" name="status_info" value="draft" {{$data->status_info=='draft' ?"checked":""}}> Draft
                </div>
                <div class='radio'>
                  <input type="radio" name="status_info" value="published" {{$data->status_info=='published' ?"checked":""}}> Published
                </div>
              </div>
            </div>
             <div class='form-group'>
              
              <div class='col-lg-offset-1 col-lg-11'>
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