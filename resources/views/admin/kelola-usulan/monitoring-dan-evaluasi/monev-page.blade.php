@extends('adminlte::page')

@section('title', 'Monitoring dan Evaluasi')

@section('content_header')
    <h1>Monitoring dan Evaluasi Usulan</h1>
    <ol class="breadcrumb">
      <li class="active"><a href="{{url('monitoring-dan-evaluasi')}}"><i class="fa fa-user"></i> Monitoring dan Evaluasi</a></li>
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
          <form action="{{url('monitoring-dan-evaluasi/ekspor-excel')}}" method="post">
            @csrf
            @include('layouts.filter-incl')
            {{-- <div class="form-group col-lg-3 col-md-3 col-xs-12">
              <label>Status Usulan</label>

              <select name="status_usulan" class="form-control" id="status_usulan" onchange="load_data()">
                <option value="">Semua</option>
        
                <option value="diterima">Diterima</option>
                <option value="ditolak">Ditolak</option>
              </select>
            </div> --}}
            <div class="form-group col-lg-3 col-md-3 col-xs-12">
              <label>Status Review</label>

              <select name="status_review" class="form-control" id="status_review" onchange="load_data()">
                <option value="">Semua</option>
                <option value="belum_input">Belum Input Reviewer</option>
                <option value="belum_review">Belum Selesai Direview</option>
                
                <option value="sudah_review">Sudah Selesai Direview</option>
                
              </select>
            </div>
            <div class="form-group col-lg-3 col-md-3 col-xs-12">

              <button style="margin-top: 27px" class="btn btn-sm btn-success" type="submit"><i class="fa fa-file-excel-o"></i> Excel Review</button>
              
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
                    <th width="20%" rowspan="2">Pelaksana</th>

                    
                    <th width="20%" colspan="2">Penilaian</th>
                    <th rowspan="2">Status</th>
                  </tr>
                  <tr>
                    <td>R1</td>
                    <td>R2</td>
                  </tr>
                </thead>
              </table>
            </div>
            </div>
            <div class="modal fade in active" id="modal-proses-usulan">
              <div class="modal-dialog modal-md">
               
                  <div class="modal-content">
                    <div class="modal-header">
                      <div class="modal-title">
                        <button class="close" data-dismiss='modal'>&times;</button>
                        <h4>Proses Usulan</h4>
                      </div>
                    </div>
                    <div class="modal-body">
                      <div class="form-group">
                        <label class="control-label col-sm-3">Tindakan</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="proses_usulan" required="">
                              <option value="">Pilih tindakan</option>
                              <option value="sedang_diajukan">Sedang Diajukan</option>
                              <option value="diterima">Diterima</option>
                              <option value="ditolak">Ditolak</option>
                              <option value="revisi">Revisi</option>
                            </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-sm-3">Catatan/Keterangan/Alasan</label>
                          <div class="col-sm-8">
                              <textarea class="form-control" name="catatan_usulan" id="catatan_usulan"></textarea>
                          </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                          <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Batal</button>
                          <button type='button' class="btn btn-sm btn-info" onclick="submit_proses_usulan()"><i class="glyphicon glyphicon-floppy-disk"></i> Proses</button>
                    </div>
                  </div>
                
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="tambah-reviewer">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="{{url('monitoring-dan-evaluasi/input-reviewer')}}" method="post" id="form-tambah-reviewer" class="form-horizontal">
                @csrf
                <div class="modal-header">
                    <div class="modal-title">
                        <h3><b><i class="glyphicon glyphicon-plus"></i> Input Reviewer</b></h3>
                    </div>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="usulan_id" id="usulan_id">
                  <input type="hidden" name="reviewer_ke" id="reviewer_ke">
                  <div class="form-group">
                      <label class="control-label col-xs-3">Nama Reviewer</label>
                      <div class="col-xs-9">
                          <select class="form-control " required="" id="dosen"  name="reviewer_id" style="width: 100%;">
                              <option value="">Pilih dosen</option>
                              
                          </select>
                      </div>
                  </div>
                    
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Batal</button>
                    <button  class="btn btn-sm btn-info"><i class="glyphicon glyphicon-floppy-disk"></i> Simpan</button>
                </div>
            </form>
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
              url:'{{url('monitoring-dan-evaluasi')}}',
              data:{
                tahun_anggaran_id:$("#tahun_anggaran_id").val(),
                jenis_usulan_id:$("#jenis_usulan_id").val(),
                sumber_dana_id:$("#sumber_dana_id").val(),
                unit_kerja_id:$("#unit_kerja_id").val(),
                skim_id:$("#skim_id").val(),
                status_usulan:$("#status_usulan").val(),
                status_review:$("#status_review").val(),
              },
            },
            columns: [
                  {data: 'DT_RowIndex',orderable:false,searchable:false},
                  //{data: 'ceklis',name:'ceklis',orderable:false,searchable:false},
                  {data: 'judul_link',name:'judul_link',orderable:false,searchable:false},
                  {data: 'penerimaan', name: 'penerimaan',orderable:false,searchable:false},
                  {data: 'pelaksana', name: 'pelaksana',orderable:false,searchable:false},
                  {data: 'r1', name: 'r1',orderable:false,searchable:false},
                  {data: 'r2', name: 'r2',orderable:false,searchable:false},
                  {data: 'status_usulan', name: 'status_usulan',orderable:false,searchable:false},
                  {data: 'judul',name:'judul',visible:false},
              ],
            aLengthMenu: [[10, 15, 25, 35, 50, 100, -1], [10, 15, 25, 35, 50, 100, "All"]], 
            responsive: !0
          });
       }
      function hapus_reviewer_monev(id,nama,judul) {
        alertify.confirm("Konfirmasi!","Apakah anda yakin menghapus sdr/i <b>"+nama+"</b> sebagai reviewer dari kegiatan yang berjudul <b>"+judul+"</b> ?",function(){
          window.location.href="{{url('monitoring-dan-evaluasi/hapus-reviewer')}}/"+id;
        },function(){

        })
      }
      function tambah_reviewer_monev(id_usulan,reviewer_ke) {
        $("#tambah-reviewer").modal('show');
        $("#usulan_id").val(id_usulan);
        $("#reviewer_ke").val(reviewer_ke);
        $("#dosen").select2({
        ajax:{
            url:"{{url('load-data-reviewer')}}",
            dataTyper:"json",
            data:function(param)
            {
                var value= {
                    search:param.term,
                }
                return value;
            },
            processResults:function(hasil)
            {
                return {
                    results:hasil,
                }
            }
        }
        });   
      }
      $("#ceksemua").click(function () { // Jika Checkbox Pilih Semua di ceklis maka semua sub checkbox akan diceklis juga
        $(".pilih").attr('checked', this.checked);
      });

      function aksi_proses_usulan() {
        totalSelect = $('.pilih:checkbox:checked').length;
        if (totalSelect==0) {
          alertify.alert("Info!","Harus ceklis setidaknya ceklis 1 usulan");
        }else{

          $("#modal-proses-usulan").modal('show');
        }
      }
      function submit_proses_usulan()
      {
        if ($("select[name='proses_usulan']").val()=="" ) {
          alertify.alert("Info!","Harus pilih tindakan terlebih dahulu");
        }else{

          alertify.confirm("Konfirmasi","Apakah anda yakin melakukan tidakan ini?",function(){
            $.ajax({
              url:"{{url('seleksi-usulan/submit-proses-usulan')}}",
              type:"post",
              data:$("#proses-usulan").serialize(),
              
              success:function(res)
              {
                if (res=='sukses') {
                  alertify.alert("Info!","Berhasil proses tindakan");
                }else{
                  alertify.alert("Info!","Gagal memproses tindakanyang dimaksud");
                }
                $("#modal-proses-usulan").modal('hide');    
                load_data();
              }

            });
          },function(){
            $("#modal-proses-usulan").modal('hide');

          });

        }
        
      }

    </script>
@stop