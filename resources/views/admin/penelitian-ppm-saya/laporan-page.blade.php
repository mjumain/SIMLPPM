@extends('adminlte::page')

@section('title', 'Laporan')

@section('content_header')
    <h1>Upload Laporan</h1>
    <ol class="breadcrumb">
      <li><a href="{{url('penelitian-ppm-saya')}}"><i class="fa fa-file"></i> Penelitian PPM Saya</a></li>
      <li class="active">Upload Laporan</li>
    </ol>
@stop

@section('content')
  <div class="row">
    <div class="col-lg-12 col-xs-12">
      @if(Session::has('alert'))
        @include('layouts.alert')
      @endif
      @if ($errors->any())
          <div class="alert alert-danger">
            <button class="close" data-dismiss="alert">&times;</button>
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif
      <div class="box box-info">
        
        <div class="box-body">
          <h3>Batas Waktu Upload Laporan</h3>
          <table class="table">
            <tr>
              <td width="20%">Batas Upload Laporan Kemajuan</td>
              <td width="2%">:</td>
              <td>{!!Tanggal::tgl_indo($data->buka_penerimaan->batas_upload_laporan_kemajuan)!!}</td>
            </tr>
            <tr>
              <td>Batas Upload Laporan Akhir</td>
              <td>:</td>
              <td>{!!Tanggal::tgl_indo($data->buka_penerimaan->batas_upload_laporan_akhir)!!}</td>
            </tr>
            <tr>
              <td>Batas Upload Artikel</td>
              <td>:</td>
              <td>{!!Tanggal::tgl_indo($data->buka_penerimaan->batas_upload_artikel)!!}</td>
            </tr>
            <tr>
              <td>Batas Upload Luaran</td>
              <td>:</td>
              <td>{!!Tanggal::tgl_indo($data->buka_penerimaan->batas_upload_luaran)!!}</td>
            </tr>
          </table>

          <br>
         
          <h3>Upload Laporan</h3>
          <table class="table">
            <tr>
              <td width="20%">Upload Laporan Kemajuan</td>
              <td width="2%">:</td>
              <td>
                @if(empty($data->buka_penerimaan->batas_upload_laporan_kemajuan))
                  Belum bisa unggah laporan kemajuan

                @elseif($data->buka_penerimaan->batas_upload_laporan_kemajuan < date('Y-m-d'))
                  Batas upload laporan kemajuan telah berakhir
                @else
                  @if(isset($data->file_laporan_kemajuan))
                  {!!Helpers::cek_dokumen($data->file_laporan_kemajuan,'laporan-kemajuan')!!}<br>
                  <i>Diunggah pada {!!Tanggal::time_indo($data->tgl_upload_laporan_kemajuan)!!}</i>
          
                    <button class="btn btn-primary btn-sm" onclick="modal_upload('laporan-kemajuan')"><i class="fa fa-edit"></i> Ganti</button> | 
                    <button class="btn btn-danger btn-sm" onclick="hapus_laporan('laporan-kemajuan')"><i class="fa fa-trash"></i> Hapus</button>
                  @else
                    <button class="btn btn-primary btn-sm" onclick="modal_upload('laporan-kemajuan')"><i class="fa fa-upload"></i> Upload</button>
                  @endif
                @endif
              </td>
            </tr>
            <tr>
              <td>Upload Laporan Akhir</td>
              <td>:</td>
              <td>
                @if(empty($data->buka_penerimaan->batas_upload_laporan_akhir))
                  Belum bisa unggah laporan akhir

                @elseif($data->buka_penerimaan->batas_upload_laporan_akhir < date('Y-m-d'))
                  Batas upload laporan akhir telah berakhir
                @else
                  @if(isset($data->file_laporan_akhir))
                  {!!Helpers::cek_dokumen($data->file_laporan_akhir,'laporan-akhir')!!}<br>
                  <i>Diunggah pada {!!Tanggal::time_indo($data->tgl_upload_laporan_akhir)!!}</i>
          
                    <button class="btn btn-primary btn-sm" onclick="modal_upload('laporan-akhir')"><i class="fa fa-edit"></i> Ganti</button> | 
                    <button class="btn btn-danger btn-sm" onclick="hapus_laporan('laporan-akhir')"><i class="fa fa-trash"></i> Hapus</button>
                  @else
                    <button class="btn btn-primary btn-sm" onclick="modal_upload('laporan-akhir')"><i class="fa fa-upload"></i> Upload</button>
                  @endif
                @endif
              </td>
            </tr>
            
            <tr>
              <td>Upload Artikel </td>
              <td>:</td>
              <td>
                @if(empty($data->buka_penerimaan->batas_upload_artikel))
                  Belum bisa unggah artikel

                @elseif($data->buka_penerimaan->batas_upload_artikel < date('Y-m-d'))
                  Batas upload artikel telah berakhir
                @else
                  @if(isset($data->file_artikel))
                  {!!Helpers::cek_dokumen($data->file_artikel,'artikel')!!}<br>
                  
                    <i>Diunggah pada {!!Tanggal::time_indo($data->tgl_upload_artikel)!!}</i>
                    <button class="btn btn-primary btn-sm" onclick="modal_upload('artikel')"><i class="fa fa-edit"></i> Ganti</button> | 
                    <button class="btn btn-danger btn-sm" onclick="hapus_laporan('artikel')"><i class="fa fa-trash"></i> Hapus</button>
                  @else
                    <button class="btn btn-primary btn-sm" onclick="modal_upload('artikel')"><i class="fa fa-upload"></i> Upload</button>
                  @endif
                @endif
              </td>
            </tr>
            <tr>
              <td>Upload Luaran </td>
              <td>:</td>
              <td>
                @if(empty($data->buka_penerimaan->batas_upload_luaran))
                  Belum bisa unggah luaran

                @elseif($data->buka_penerimaan->batas_upload_luaran < date('Y-m-d'))
                  Batas upload luaran telah berakhir
                @else
                  @if(isset($data->file_luaran))
                  {!!Helpers::cek_dokumen($data->file_luaran,'luaran')!!}<br>
                  
                    <i>Diunggah pada {!!Tanggal::time_indo($data->tgl_upload_artikel)!!}</i>
                    <button class="btn btn-primary btn-sm" onclick="modal_upload('luaran')"><i class="fa fa-edit"></i> Ganti</button> | 
                    <button class="btn btn-danger btn-sm" onclick="hapus_laporan('luaran')"><i class="fa fa-trash"></i> Hapus</button>
                  @else
                    <button class="btn btn-primary btn-sm" onclick="modal_upload('luaran')"><i class="fa fa-upload"></i> Upload</button>
                  @endif
                @endif
              </td>
            </tr>
            <tr>
              <td>Link Luaran </td>
              <td>:</td>
              <td>
                @if(empty($data->buka_penerimaan->batas_upload_luaran))
                  Belum bisa unggah luaran

                @elseif($data->buka_penerimaan->batas_upload_luaran < date('Y-m-d'))
                  Batas upload luaran telah berakhir
                @else
                  @if(isset($data->link_jurnal))
                  {!!$data->link_jurnal!!}<br>
                  
                    <br>
                    <button class="btn btn-primary btn-sm" onclick="modal_link_luaran()"><i class="fa fa-edit"></i> Ganti</button> | 
                    <button class="btn btn-danger btn-sm" onclick="hapus_link_luaran()"><i class="fa fa-trash"></i> Hapus</button>
                  @else
                    <button class="btn btn-primary btn-sm" onclick="modal_link_luaran()"><i class="fa fa-upload"></i> Isi Link Luaran</button>
                  @endif
                @endif
              </td>
            </tr>
          </table>
          <h3>Penyerahan Hardcopy</h3>
          @include('layouts.hardcopy-incl')
        </div>
          
      
        
      </div>
    </div>
  </div>
  <div class="modal fade in active" id='modal'>
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <form enctype="multipart/form-data" method="post" action="" id="form-modal">
          @csrf

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label id='label-modal'>File Upload</label>
              <input type="file" required="" name="file_laporan" class="form-control" accept="application/pdf">
              <small id='pesan'></small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Upload</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="modal fade in active" id='modal-link-luaran'>
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <form enctype="multipart/form-data" method="post" action="" id="form-modal-luaran">
          @csrf

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Input Link Luaran</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label id='label-modal'>Link Luaran</label>
              <textarea class="form-control" name="link_jurnal" rows="5" required="">{!!$data->link_jurnal!!}</textarea>
              <small > contoh https://online-journal.unja.ac.id/JUSS/article/view/8919/5828</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          </div>
        </form>
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
    function modal_upload(jenis) {
      if (jenis=='laporan-kemajuan') {
        var judul_modal="Upload Laporan Kemajuan";
        var link="{{url('penelitian-ppm-saya/post')}}/laporan-kemajuan/{{encrypt($data->id_usulan)}}";
        var pesan="Fomat file .pdf maksimal ukuran 5 MB";
      }
      if (jenis=='laporan-akhir') {
        var judul_modal="Upload Laporan Akhir";
        var link="{{url('penelitian-ppm-saya/post')}}/laporan-akhir/{{encrypt($data->id_usulan)}}";
        var pesan="Fomat file .pdf maksimal ukuran 10 MB";
      }
      if (jenis=='laporan-akhir-turnitin') {
        var judul_modal="Upload Uji Turnitin Laporan Akhir";
        var link="{{url('penelitian-ppm-saya/post')}}/laporan-akhir-turnitin/{{encrypt($data->id_usulan)}}";
        var pesan="Fomat file .pdf maksimal ukuran 10 MB";
      }
      if (jenis=='artikel') {
        var judul_modal="Upload Artikel";
        var link="{{url('penelitian-ppm-saya/post')}}/artikel/{{encrypt($data->id_usulan)}}";
        var pesan="Fomat file .pdf maksimal ukuran 5 MB";
      }
      if (jenis=='luaran') {
        var judul_modal="Upload Luaran";
        var link="{{url('penelitian-ppm-saya/post')}}/luaran/{{encrypt($data->id_usulan)}}";
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
     function modal_link_luaran() {
      
      $('#modal-link-luaran').modal('show');
     
      $("#form-modal-luaran").attr('action',"{{url('penelitian-ppm-saya/post')}}/link-luaran/{{encrypt($data->id_usulan)}}");
      
    }
    function hapus_link_luaran()
    {
      
      alertify.confirm('Konfimasi','Yakin hapus  link luaran ?',function(){
        window.location.href="{{url('penelitian-ppm-saya/hapus-laporan/')}}"+'/link-luaran/{{encrypt($data->id_usulan)}}'
      },function(){})
    }
  </script>
 
 
@stop