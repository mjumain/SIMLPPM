@extends('adminlte::page')

@section('title', 'Detail Usulan')

@section('content_header')
    <h1>Detail Usulan</h1>
    <ol class="breadcrumb">
      <li><a href="{{url('pendaftaran-usulan')}}"><i class="fa fa-wrench"></i> Pendaftaran Usulan</a></li>
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
        
        <div class="box-body">
         @include('layouts.data-penerimaan-incl')
          @include('layouts.data-usulan-incl')
          @include('layouts.hasil-review-proposal-min-incl')
          
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
 
@stop