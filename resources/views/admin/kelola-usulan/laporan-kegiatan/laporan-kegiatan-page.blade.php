@extends('adminlte::page')

@section('title', 'Laporan Kegiatan')

@section('content_header')
    <h1>Laporan Kegiatan</h1>
    <ol class="breadcrumb">
      <li class="active"><a href="{{url('laporan-kegiatan')}}"><i class="fa fa-books"></i> Laporan Kegiatan</a></li>
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
          <form action="{{url('laporan-kegiatan/ekspor-excel')}}" method="post">
            @csrf
            @include('layouts.filter-incl')
            
            <div class="form-group col-lg-3 col-md-3 col-xs-12">
              <label>Target Luaran</label>

              <select name="luaran_id" class="form-control select2" id="luaran_id" onchange="load_data()">
                <option value="">Semua</option>
                @foreach(App\Luaran::get() as $luaran)
                <option value="{{$luaran->id_luaran}}">{{$luaran->nama_luaran}}</option>
                @endforeach
                
              </select>
            </div>
            <div class="form-group col-lg-3 col-md-3 col-xs-12">
              <label>Status Laporan</label>

              <select name="laporan" class="form-control " id="laporan" onchange="load_data()">
                <option value="">Semua</option>
                
                <option value="belum_lengkap_upload">Belum Lengkap Uplaod</option>
                <option value="belum_lengkap_hardcopy">Belum Lengkap Hardcopy</option>
                
                
              </select>
            </div>
            <div class="form-group col-lg-3 col-md-3 col-xs-12" onchange="load_data()">
              <label>Per Dosen</label>

              <select class="form-control select2dosen" name="id_peg" id="id_peg">
               <option value="">Semua</option>
                
             </select>
            </div>
            <div class="form-group col-lg-3 col-md-3 col-xs-12">

              <button style="margin-top: 27px" class="btn btn-sm btn-success" type="submit"><i class="fa fa-file-excel-o"></i> Excel Laporan</button>
              
            </div>
          </form>
        </div>
        <div class="box-body">
          <form class="form-horizontal" id='proses-usulan'>
          @csrf
          <div class="box-body" style="min-height: 480px;">
            
            <div class="table-responsive">
              <table class="table table-striped table-bordered" id="table">
                <thead>
                  <tr>
                    <th width="2%" rowspan="2">No</th>
                    {{-- <th width="5%" rowspan="2"><input type="checkbox" id="ceksemua"></th> --}}
                    <th width="30%" rowspan="2">Judul</th>
                    <th width="20%" rowspan="2">Pendanaan</th>
                    <th width="17%" rowspan="2">Pelaksana</th>

                    
                    <th  colspan="2">Laporan</th>
                    
                  </tr>
                  <tr>
                    <td>Unggah Laporan</td>
                    <td>Hardcopy Laporan</td>
                  </tr>
                </thead>
              </table>
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
    <script>

       load_data();
       $('.select2').select2({

        placeholder:"Semua",
        allowClear:true,
       });
       function load_data()
       {
          $('#table').DataTable( {
            bAutoWidth: false,
            bLengthChange: true,
            iDisplayLength: 10,
            searching: true,
            processing: true,
            serverSide: true,
            bDestroy: true,
            bStateSave: true,
            ajax: {
              url:'{{url('laporan-kegiatan')}}',
              data:{
                tahun_anggaran_id:$("#tahun_anggaran_id").val(),
                jenis_usulan_id:$("#jenis_usulan_id").val(),
                sumber_dana_id:$("#sumber_dana_id").val(),
                unit_kerja_id:$("#unit_kerja_id").val(),
                skim_id:$("#skim_id").val(),
                luaran_id:$("#luaran_id").val(),
                laporan:$("#laporan").val(),
                id_peg:$("#id_peg").val(),
                
              },
            },
            columns: [
                  {data: 'DT_RowIndex',orderable:false,searchable:false},
                  
                  {data: 'judul_link',name:'judul_link',orderable:false,searchable:false},
                  {data: 'penerimaan', name: 'penerimaan',orderable:false,searchable:false},
                  {data: 'pelaksana', name: 'pelaksana',orderable:false,searchable:false},
                  {data: 'soft', name: 'soft',orderable:false,searchable:false},
                  {data: 'hard', name: 'hard',orderable:false,searchable:false},
                  
                  {data: 'judul',name:'judul',visible:false},
              ],
            aLengthMenu: [[10, 15, 25, 35, 50, 100, -1], [10, 15, 25, 35, 50, 100, "All"]], 
            responsive: !0
          });
       }
     
      function ceklis_hardcopy(id,jenis)
      {
        var cek=$("#"+jenis+id).is(':checked');
        var status="";
        if (cek==true) {
          status=1;
        }else{
          status=0;
        }
        $.ajax({
          url:"{{url('laporan-kegiatan/ceklis')}}",
          data:{
            id_usulan:id,
            jenis:jenis,
            status:status,
            _token:"{{csrf_token()}}",

          },
          type:'post',
          beforeSend:function(){
            $("#overlay").fadeIn(200);
          },success:function(d)
          {
            console.log(d);
            $("#overlay").fadeOut(200);
          }

        })
      }
      $(".select2dosen").select2({
      placeholder:"Tulis nama atau nidn dosen",
      allowClear:true,
      ajax:{
          url:"{{url('load-dosen-pegawai-public')}}",
          dataTyper:"json",
          data:function(param)
          {
              var value= {
                  search:param.term,
              };
              return value;
          },
          processResults:function(hasil)
          {
              return {
                  results:hasil,
              };
          }
      }
    });

     

    </script>
@stop