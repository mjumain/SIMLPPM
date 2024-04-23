@extends('adminlte::page')

@section('title')
  Setting Konten Statis
@stop



@section('content_header')
    <h1>Setting Konten Statis</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-edit"></i>Setting</a></li>
      <li class="active"><a href="{{url('admin/setting-konten-statis')}}"> Setting Konten Statis</a></li>
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
         
          <form class="form-horizontal" action="{{url('admin/setting-konten-statis/'.$data->id_konten_statis)}}" method="post" id='form-deskripsi' enctype="multipart/form-data">
            @csrf
            @method('put')

            <div class='form-group'>
              <label class='control-label col-lg-2'>Pengumuman Singkat (home)</label>
              <div class='col-lg-10'>
                <textarea class='form-control' id='sekilas_unja' name='sekilas_unja' placeholder="Tulis isi sekilas UNJA" rows="20">{!!$data->sekilas_unja!!}</textarea>
              </div>
            </div>
            <div class='form-group'>
              <label class='control-label col-lg-2'>Email</label>
              <div class='col-lg-10'>
                <input type="text" name="email_pt" class="form-control" placeholder="email" value="{{$data->email_pt}}">
              </div>
            </div>
            <div class='form-group'>
              <label class='control-label col-lg-2'>Alamat</label>
              <div class='col-lg-10'>
                <textarea class="form-control" name="alamat_pt" placeholder="Alamat" rows="3">{{$data->alamat_pt}}</textarea>
              </div>
            </div>
            <div class='form-group'>
              <label class='control-label col-lg-2'>Telepon</label>
              <div class='col-lg-10'>
                <input type="text" name="telepon_pt" class="form-control" value="{{$data->telepon_pt}}">
              </div>
            </div>
            <div class='form-group'>
              <label class='control-label col-lg-2'>Jam Kerja</label>
              <div class='col-lg-10'>
                <textarea class="form-control" name="jam_kerja_pt" placeholder="Jam Kerja" rows="3">{{$data->jam_kerja_pt}}</textarea>
              </div>
            </div>
            <div class='form-group'>
              <label class='control-label col-lg-2'>Link Profil UNJA</label>
              <div class='col-lg-10'>
                <textarea class="form-control" name="embed_tentang_unja" placeholder="Link Embbed Video Tentang Unja" rows="3">{{$data->embed_tentang_unja}}</textarea>
                
              </div>
            </div>
            <div class='form-group'>
              <label class='control-label col-lg-2'>Link Tutorial</label>
              <div class='col-lg-10'>
                <textarea class="form-control" name="embed_tutorial" placeholder="Link Embbed Video Tutorial" rows="3">{{$data->embed_tutorial}}</textarea>
              </div>
            </div>
            <div class="form-group">
              <label class='control-label col-lg-2'>Upload Step Pendaftaran</label>
              <div class='col-lg-10'>
                <input type="file" name="gambar_step_pendaftaran" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class='control-label col-lg-2'>Gambar Step Pendaftaran</label>
              <div class='col-lg-10'>
                <img src="{{url('img/'.$data->gambar_step_pendaftaran)}}" width="300px" height="400px">
              </div>
            </div>

             <div class='form-group'>
              
              <div class='col-lg-offset-2 col-lg-10'>
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
  

@stop

@section('js')
  
  @include('plugins.alertify-js')
  @include('plugins.tinymce-js')
  <script>
     tinymce.init({
        selector: 'textarea#sekilas_unja',
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
        images_upload_url: '{{url("admin/file-manager-direct")}}',
        images_upload_handler : function(blobInfo, success, failure) {
          var xhr, formData;
          xhr = new XMLHttpRequest();
          xhr.withCredentials = false;
          xhr.open('POST', '{{url("admin/file-manager-direct")}}');
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