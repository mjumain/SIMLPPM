@extends('adminlte::page')

@section('title', $data['title'])

@section('content_header')
    <h1>{{$data['title']}}</h1>
    <ol class="breadcrumb">
      <li class="active"><a href="{{url()->current()}}"><i class="fa fa-edit"></i>{{$data['title']}}</a></li>
    </ol>
@stop

@section('content')
  <div class="row">
    
      <div class="col-lg-4 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3 id="belum-direview">0</h3>

            <p>Usulan belum direview</p>
          </div>
          <div class="icon">
            <i class="fa fa-file"></i>
          </div>
          <a onclick="loadData('belum')" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-4 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3 id="sedang-direview">0</h3>

            <p>Usulan sedang direview</p>
          </div>
          <div class="icon">
            <i class="fa fa-edit"></i>
          </div>
          <a onclick="loadData('proses')" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-4 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3 id="sudah-direview">0</h3>

            <p>Usulan sudah direview</p>
          </div>
          <div class="icon">
            <i class="glyphicon glyphicon-check"></i>
          </div>
          <a onclick="loadData('sudah')" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
    
    
  </div>
  <div class="row">
    <div class="col-lg-12 col-xs-12">
      
      <div class="box box-info">
        <div class="box-header with-border">
          <form>
            <div class="form-group col-lg-3">
              <label>Tahun Anggaran</label>
              <select class="form-control" name="tahun_anggaran_id" id="tahun_anggaran_id" onchange="loadData()">
                <option value="">Semua</option>
                @foreach(App\TahunAnggaran::orderBy('tahun','desc')->get() as $t)
                <option value="{{$t->id_tahun_anggaran}}" {{$t->id_tahun_anggaran== $data['tahun']->id_tahun_anggaran ? 'selected':''}}>{{$t->tahun}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-lg-3">
              <label>Jenis Usulan</label>
              <select class="form-control" name="jenis_usulan_id" id="jenis_usulan_id" onchange="loadData()">
                <option value="">Semua</option>
                @foreach(App\JenisUsulan::get() as $t)
                <option value="{{$t->id_jenis_usulan}}" >{{$t->jenis_usulan}}</option>
                @endforeach
              </select>
            </div>


          </form>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="table">
              <thead>
                <tr>
                  <th width="2%">No</th>
                  <th width="15%">Pendanaan</th>
                  <th width="60%">Judul</th>
                  
                  <th>Status Usulan</th>
                  <th>Status Review</th>
                  <th width="8%">#</th>
                </tr>
                
              </thead>
              
            </table>
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
    loadData();
    function loadData(status_r=null)
    {
      var table=$('#table').DataTable( {
          bAutoWidth: false,
          bLengthChange: true,
          iDisplayLength: 10,
          searching: true,
          processing: true,
          serverSide: true,
          bDestroy: true,
          bStateSave: true,
          ajax: {
            url:'{{url('review/'.$data['jenis'])}}',
            data:{
              tahun_anggaran_id:$("#tahun_anggaran_id").val(),
              jenis_usulan_id:$("#jenis_usulan_id").val(),
              status_review:status_r,
            },
          },
          columns: [
                {data: 'DT_RowIndex',orderable:false,searchable:false},
                
                {data: 'pendanaan',name:'pendanaan',orderable:false,searchable:false},
                {data: 'judul', name: 'judul'},
                
                {data: 'status_usulan',name:'status_usulan',orderable:false,searchable:false},
                {data: 'status_review',name:'status_review',orderable:false,searchable:false},
                
                {data: 'aksi', name: 'aksi',orderable:false,searchable:false},
                
            ],
          fnInitComplete:function(oSetting,json){
            $("#belum-direview").html(json.belum_review);
            $("#sudah-direview").html(json.sudah_review);
            $("#sedang-direview").html(json.dalam_proses);
          },
          aLengthMenu: [[10, 15, 25, 35, 50, 100, -1], [10, 15, 25, 35, 50, 100, "All"]], 
          responsive: !0
        });
    }
  </script>
 
    
@stop