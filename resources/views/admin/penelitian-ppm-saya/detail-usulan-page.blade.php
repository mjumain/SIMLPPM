@extends('adminlte::page')

@section('title', 'Detail Penelitian dan PPM')

@section('content_header')
    <h1>Detail Penelitian dan PPM</h1>
    <ol class="breadcrumb">
      <li><a href="{{url('penelitian-ppm-saya')}}"><i class="fa fa-file"></i> Penelitian PPM Saya</a></li>
      <li class="active"> Detail Penelitian dan PPM</li>
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
          <ul class="nav nav-tabs" id="#myTab">
            <li class="active"><a data-toggle="tab" href="#usulan">Data Usulan</a></li>
            <li><a href="#hasil-review" data-toggle="tab">Hasil Review</a></li>
            <li><a href="#upload" data-toggle="tab">Upload Laporan</a></li>
            <li><a href="#hardcopy" data-toggle="tab">Penyerahan Hardcopy Laporan</a></li>
            
          </ul>
          <div class="tab-content">
            <div id="usulan" class="tab-pane fade in active">
              @include('layouts.data-penerimaan-incl')
              @include('layouts.data-usulan-incl')
            </div>
            <div id="hasil-review" class="tab-pane fade">
              @include('layouts.hasil-review-proposal-min-incl')
              @include('layouts.hasil-review-monev-min-incl')
            </div>
            <div id="upload" class="tab-pane fade">
              @include('layouts.data-laporan-incl')
            </div>
            <div id="hardcopy" class="tab-pane fade">
              @include('layouts.hardcopy-incl')
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
  <script type="text/javascript">
    $(document).ready(function() {
      if (location.hash) {
          $("a[href='" + location.hash + "']").tab("show");
      }
      $(document.body).on("click", "a[data-toggle='tab']", function(event) {
          location.hash = this.getAttribute("href");
      });
    });
    $(window).on("popstate", function() {
        var anchor = location.hash || $("a[data-toggle='tab']").first().attr("href");
        $("a[href='" + anchor + "']").tab("show");
    });
  </script>
 
@stop