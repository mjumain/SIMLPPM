@extends('adminlte::page')

@section('title', 'Edit Komponen Borang '.$judul_tahap.' '.$jenis_skema->nama_jenis_skema)

@section('content_header')

    <h1>{{'Edit Komponen Borang '}}</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-wrench"></i> Setup Aplikasi</a></li>
      <li><a href="{{url('/setup-borang')}}"> Setup Borang</a></li>
      <li ><a href="{{url('/setup-borang/input-borang/'.$tahap.'/'.$jenis_skema->id_jenis_skema)}}"> {{'Borang '.$judul_tahap.' '.$jenis_skema->nama_jenis_skema}}</a></li>
      <li class="active"> Edit</li>
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
          
          <form class="form-horizontal" action="{{url('/setup-borang/input-borang/'.$tahap.'/'.$jenis_skema->id_jenis_skema.'/'.$borang->id_borang)}}" method="post" onsubmit="return confirm('Apakah anda sudah yakin menyimpan data?')" id="form-create">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-xs-12">

                @csrf
                
                <div class="form-group">
                  <label class="control-label col-xs-2">Komponen Penilaian</label>
                  <div class="col-xs-8">
                    <textarea class="form-control" name="komponen_penilaian" required="" id="komponen_penilaian">{!! $borang->komponen_penilaian !!}</textarea>

                  </div>

                </div>
                <div class="form-group">
                  <label class="control-label col-xs-2">Bobot Skor (%)</label>
                  <div class="col-xs-8">
                    <input type="text" name="bobot"  maxlength="2"  class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{$borang->bobot}}" placeholder="Bobot penilaian" required="">
                    <small> Input hanya angka saja, contoh: 30</small>
                  </div>

                </div>
                <div class="input_fields_wrap">
                  
                  @foreach($borang->skor_borang as $s)
                  <div class="form-group">
                    <input type="hidden" name="id_skor_borang[]" value="{{$s->id_skor_borang}}">
                    @if($loop->iteration==1)
                    <label class="control-label col-xs-2">Pilihan Skor</label>
                    @endif
                    <div class="{{($loop->iteration > 1 ) ? 'col-xs-offset-2 ':'' }} col-xs-3">
                      <input type="text" name="skor[]" maxlength="2" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{$s->skor}}"  placeholder="Skor" required="">
                      <small>Input hanya angka</small>
                    </div>
                    <div class="col-xs-4">
                      <input type="text" name="keterangan[]"  class="form-control"  placeholder="Keterangan Skor" value="{{$s->keterangan}}" required="" value="Buruk">
                    </div>
                    @if($loop->iteration>=3)
                    <div class="col-xs-1">
                      <a href="{{url('delete-skor-borang/'.$s->id_skor_borang)}}" onclick="return confirm('Hapus skor borang ini?')" class="btn btn-danger btn-md"><i class="glyphicon glyphicon-remove"> </i></a>
                    </div>
                    @endif

                  </div>

                  @endforeach
                </div> 
               
                <div class="form-group">
                  <div class="col-xs-offset-2 col-lg-8">
                    <button type="submit"  class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Simpan</button> | <button type="button"  class="btn btn-sm btn-success add_field_button"><i class="glyphicon glyphicon-plus"></i> Tambah Pilihan</button>
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
  @include('plugins.tinymce-js')
  
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
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper       = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
    
    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
      e.preventDefault();
      if(x < max_fields){ //max input box allowed
        x++; //text box increment
        $(wrapper).append("<div class=\"form-group\">"+
                    
                            "<div class=\"col-xs-offset-2 col-xs-3\">"+
                              "<input type=\"text\" name=\"skor_baru[]\" maxlength=\"2\" class=\"form-control\" onkeypress=\"return event.charCode >= 48 && event.charCode <= 57\" value=\"0\"  placeholder=\"Skor\" required=\"\">"+
                              "<small>Input hanya angka</small>"+
                            "</div>"+
                            "<div class=\"col-xs-4\">"+
                              "<input type=\"text\" name=\"keterangan_baru[]\"  class=\"form-control\"  placeholder=\"Keterangan Skor\" required=\"\" value=\"Tulis sesuatu\">"+
                            "</div>"+
                            "<div class=\"col-xs-1\">"+
                              "<button type=\"button\" class=\"btn btn-danger btn-md remove_field\"><i class=\"glyphicon glyphicon-remove\"></i> </button>"+
                            "</div>"+

                          "</div>"); //add input box
          
      }
    });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
      e.preventDefault(); $(this).parent('div').parent('div').remove(); x--;
    });
    tinymce.init({
        selector: 'textarea#komponen_penilaian',
        height: 400,
        
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