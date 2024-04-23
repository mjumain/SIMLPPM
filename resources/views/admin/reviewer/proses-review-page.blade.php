@extends('adminlte::page')

@section('title', $header['jenis'])

@section('content_header')
    <h1>{{$header['judul']}}</h1>
    <ol class="breadcrumb">
      <li><a href="{{url('review/'.$jenis)}}"><i class="fa fa-wrench"></i>{{$header['judul']}}</a></li>
      <li class="active"> Proses Review</li>
    </ol>
@stop

@section('content')
  <div class="row">
    <div class="col-lg-12 col-xs-12">
      @if(Session::has('alert'))
        @include('layouts.alert')
      @endif
      <div class="panel panel-default">
        <div class="panel-body">
          @if($borang->count() > 0)
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#daftar-usulan">Data Usulan</a></li>
              <li><a href="#borang-review" data-toggle="tab">Borang {{$header['judul']}}</a></li>
              
              
            </ul>
            <div class="tab-content">
              <div id="daftar-usulan" class="tab-pane fade in active">
                @include('layouts.data-usulan-min-incl')
                @if($jenis=='evaluasi-hasil')
                @include('layouts.data-laporan-incl')
                @endif
              </div>
              <div id="borang-review" class="tab-pane fade">
                  <br>
                  <form class="form-horizontal" action="{{url('review/borang/save-review/'.encrypt($data->id_usulan))}}" method="post" id="form-borang">
                      <input type="hidden" name="status_review" id="status_review">
                      @csrf
                      <div class="table-responsive">
                          <table class='table table-bordered table-striped table-hover' id="table-sumber-dana">
                              <thead>
                                  <tr class="text-center">
                                      <th width="2%">No</th>
                                      
                                      <th width="63%">Komponen Penilaian</th>
                                      <th width="7%">Bobot (%)</th>
                                      <th>Opsi Skor</th>
                                      <th width="7%">Nilai</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  
                                  @foreach($borang as $b)
                                  
                                  <tr style="font-weight: normal;">
                                      <td>{{$loop->iteration}}</td>
                                      <td>{!!$b->komponen_penilaian!!}</td>
                                      <td> {{$b->bobot}} </td>
                                      <td>
                                          @php
                                          $nilai=0;
                                          $id_skor=0;
                                          $a=DB::table('usulan_has_borang as a')
                                                  ->where('a.usulan_id',$data->id_usulan)
                                                  ->where('a.borang_id',$b->id_borang)
                                                  ->where('a.reviewer_id',auth()->user()->pegawai->id_pegawai)
                                                  ->first();
                                          if ($a) {
                                      
                                              $nilai=$a->nilai;
                                              $id_skor=$a->skor_borang_id;
                                          }
                                          @endphp
                                          @if($reviewer_usulan->status_review!='sudah')
                                          <select class="form-control skor_borang" id="{{$b->id_borang}}"  onchange="save_skor('{{$b->id_borang}}')">
                                          <option value="0">Pilih Skor...</option>
                                          @foreach($b->skor_borang as $s)
                                          <option value="{{$s->id_skor_borang}}" {{$s->id_skor_borang==$id_skor ?'selected':''}}>{{$s->skor.' ('.$s->keterangan.')'}}</option>
                                          @endforeach
                                          </select>
                                          @else
                                          @php
                                              $b=App\SkorBorang::find($id_skor);
                                          @endphp
                                              {{$b->skor.' ('.$b->keterangan.')'}}
                                          @endif
                                          
                                      </td>
                                      
                                      
                                      <td>
                                          
                                          <span id="nilai{{$b->id_borang}}">{{$nilai}}</span>
                                      </td>
                                  </tr>
                                  @endforeach
                                  <tr>
                                      <td colspan="3" align="center"><b>Total Nilai</b></td>
                                      <td id="total-nilai" colspan="2">{{number_format($reviewer_usulan->nilai,2)}}</td>
                                  </tr>
                              </tbody>
                          
                          </table>
                      </div>
                      <br>
                      <input type="hidden" name="jenis_review" value="{{$header['jenis']}}">
                      <div class="form-group">
                          <label class="control-label col-lg-2">Komentar</label>
                          <div class="col-lg-10">
                              <textarea class="form-control" {{$reviewer_usulan->status_review!='sudah' ? '':'disabled'}} name="komentar" id="komentar" rows="8" >{{$reviewer_usulan->komentar}}</textarea>
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="control-label col-lg-2">Rekomendasi</label>
                          <div class="col-lg-2">

                              <select class="form-control" name="rekomendasi" {{$reviewer_usulan->status_review!='sudah' ? '':'disabled'}} >
                                  <option value="0">Pilih rekomendasi</option>
                                  <option value="Layak" {{$reviewer_usulan->rekomendasi=='Layak' ?'selected':'' }}>Layak</option>
                                  <option value="Tidak Layak" {{$reviewer_usulan->rekomendasi=='Tidak Layak' ?'selected':'' }}>Tidak Layak</option>
                              </select>
                          </div>
                      </div>
                      <div class="form-group">
                          
                              @if($reviewer_usulan->status_review!='sudah')
                              <div class="col-lg-offset-5 col-lg-2">
                             <button class="btn btn-info btn-lg" type="button" onclick="modal()" ><i class="glyphicon glyphicon-floppy-disk"></i> Simpan Review</button>
                              </div>
                             @else
                             <div class="col-lg-offset-2 col-lg-8">
                             <div class="alert alert-info">
                                 <b>Sudah direview pada tanggal {{Tanggal::time_indo($reviewer_usulan->waktu_review)}}</b>
                             </div>
                              </div>
                             @endif

                            
                           
                      </div>
                      

                  </form>
              </div>
            </div>
          @else
            <div class="alert alert-info">
                Tidak ada kompenen yang harus di evaluasi
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  
  <div class="modal fade in active" id="modal-review">
    <div class="modal-dialog modal-md" >
        <div class="modal-content">
            <dir class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <div class="modal-title">
                    Konfirmasi Simpan Review
                </div>
            </dir>
            <div class="modal-body">
                <ul class="list-group">
                  <li class="list-group-item">
                      <input type="radio" name="simpan" onclick="simpan()"  value="simpan_sementara" checked="">
                      Simpan Sementara Review (Bisa dirubah sewaktu-waktu)
                  </li>
                  <li class="list-group-item">
                      <input type="radio" name="simpan" onclick="simpan()" value="simpan_permanen">
                      Simpan Sebagai Review Final (tidak bisa dirubah)
                  </li>
                  
                </ul>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-md" type=" button" onclick="konfirm()">Simpan</button>
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
  $(document).ready(function(){
          
      $("#rev").addClass('active');
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
  function save_skor(id)
    {
        $.ajax({
            url:"{{url('review/borang/save-skor')}}",
            type:"post",
            data:{

                usulan_id:"{{$data->id_usulan}}",
                borang_id:id,
                reviewer_id:"{{auth()->user()->pegawai->id_pegawai}}",
                skor_borang_id:$("#"+id).val(),
                jenis_review:"{{$header['jenis']}}"
            },
            beforeSend:function(){
              $("#overlay").fadeIn(100);
            },
            success:function(data)
            {   
                $("#nilai"+id).html(data.nilai);
                $("#total-nilai").html(data.nilai_total);
                $("#overlay").fadeOut(100);
            }

        });
    }


    function modal()
    {
        $("#modal-review").modal('show');
        $("#status_review").val($("input[name='simpan']:checked").val());

    }

    function simpan()
    {
        $("#status_review").val($("input[name='simpan']:checked").val());
        
    }
    function konfirm()
    {   var isValid=true;
        if ($("#status_review").val()=='simpan_sementara') {
            $("#form-borang").submit();
        }else{
            $("select").each(function() {
               var element = $(this);
               if (element.val() == "0"||element.val() == 0) {
                    isValid = false;
               }

               
            });
            if ($("#komentar").val()==null||$("#komentar").val()=="" ) {
                 isValid=false;
            }
        }


        if (isValid==false) {
            alert("Maaf masih ada input penilaian yang masih kosong, silahkan dicek kembali");
            $("#modal-review").modal('hide');            
        }else{
            $("#form-borang").submit();
        }
    }
    function modal_upload(jenis) {
      if (jenis=='proposal-turnitin') {
        var judul_modal="Upload Proposal hasil Uji Turnitin";
        var link="{{url('penelitian-ppm-saya/post')}}/proposal-turnitin/{{encrypt($data->id_usulan)}}";
        var pesan="Fomat file .pdf maksimal ukuran 5 MB";
      }
      
      $('#modal').modal('show');
      $(".modal-title").html(judul_modal);
      $("#form-modal").attr('action',link);
      $("#pesan").html(pesan);
    }
    function hapus_laporan(jenis)
    {
      var pesan = jenis.replace("-", " ");
      alertify.confirm('Konfimasi','Yakin hapus file '+pesan+' ?',function(){
        window.location.href="{{url('penelitian-ppm-saya/hapus-laporan/')}}/"+jenis+'/{{encrypt($data->id_usulan)}}'
      },function(){})
    }
  </script>
    
@stop