@extends('adminlte::page')

@section('title', 'Detail Usulan')

@section('content_header')
    <h1>Detail Usulan</h1>
    <ol class="breadcrumb">
      <li><a href="{{url('seleksi-usulan')}}"><i class="fa fa-book"></i> Seleksi Usulan</a></li>
      <li class="active"> Detail Usulan</li>
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
          <div class="pull-right">
            <a class="btn btn-primary btn-md" href="{{url('seleksi-usulan/edit/'.encrypt($data->id_usulan).'/edit-by-admin')}}"><i class="fa fa-edit"> Edit</i></a>
          </div>
        </div>
        <div class="box-body">
          <ul class="nav nav-tabs" id="#myTab">
            <li class="active"><a data-toggle="tab" href="#usulan">Data Usulan</a></li>
            <li><a href="#hasil-review" data-toggle="tab">Hasil Review Proposal</a></li>
            
            
          </ul>
          <div class="tab-content">
            <div id="usulan" class="tab-pane fade in active">
              @include('layouts.data-penerimaan-incl')
              @include('layouts.data-usulan-incl')
            </div>
            <div id="hasil-review" class="tab-pane fade">
              @include('layouts.hasil-review-proposal-incl')
              
            </div>
            
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
    function batalkan_review(id) {
      alertify.confirm("Konfrmasi","Apakah anda yakin mebatalkan hasil review ini, jika iya maka harap hubungi reviewer untuk melakukan review ulang! ",function(){
        window.location.href="{{url('review/batalkan-review')}}/"+id;
      },function(){});
    }
  </script>
 
@stop