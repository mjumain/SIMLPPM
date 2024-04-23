@extends('adminlte::page')

@section('title', 'Manage Menu')

@section('content_header')
    <h1>Setup Page Header<small>Setup website page header</small></h1>
    <ol class="breadcrumb">
      <li class="active"><a href="{{url('admin/page-header')}}"><i class="fa fa-wrench"></i> Setup Page Header</a></li>
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
            <a href="#add-menu" data-toggle="modal"  class="btn btn-sm btn-primary"><i class="fa fa-wrench"></i> Setup Page Header</a>
          </div>
        </div>
        <div class="box-body">
          
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