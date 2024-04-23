<div class="row">
  <div class="col-xs-12 col-sm-12">
    <h3> Informasi Usulan</h3>
    <div class="table-responsive">

      <table class="table table-striped">

        <tr>

          <td width="26%">Status</td>
          <td width="2%">:</td>
          <td width="72%" colspan="2">
            {!!Helpers::status_usulan($data->status)!!}
            @if($data->waktu_ajukan_revisi_proposal!=null)
              <br>Proposal Direvisi pada : {{Tanggal::time_indo($data->waktu_ajukan_revisi_proposal)}}
            @endif
          </td>
        </tr>
        <tr>
          <td>Bidang {{$data->buka_penerimaan->jenis_usulan->jenis_usulan}}</td>
          <td>:</td>
          <td colspan="2">{{$data->tema->bidang->nama_bidang}}</td>
        </tr>
        <tr>
          <td>Tema {{$data->buka_penerimaan->jenis_usulan->jenis_usulan}}</td>
          <td>:</td>
          <td colspan="2">{{$data->tema->nama_tema}}</td>
        </tr>
        
        
        <tr>
          <td>Judul</td>
          <td>:</td>
          <td colspan="2">{{$data->judul}}</td>
        </tr>
        
        <tr>
          <td>Jenis {{$data->buka_penerimaan->jenis_usulan->jenis_usulan}}</td>
          <td>:</td>
          <td colspan="2">{{$data->jenis_skema->nama_jenis_skema}}</td>
        </tr>
        <tr>
            <td>Nilai TKT</td>
            <td>:</td>
            <td colspan="2">{!!$data->tkt->nilai_tkt!!}</td>
        </tr>
  
        
        <tr>
          <td>Dana Perjudul</td>
          <td>:</td>
          <td colspan="2">{{Uang::format_uang($data->dana_perjudul)}}</td>
        </tr>
        <tr>
          <td>Lokasi</td>
          <td>:</td>
          <td colspan="2">{{$data->nama_desa.", ".$data->lokasi->nama_wilayah.", ".$data->lokasi->parent->nama_wilayah,', '.$data->lokasi->parent->parent->nama_wilayah}}</td>
        </tr>
        <tr>
          <td>Lama Kegiatan</td>
          <td>:</td>
          <td colspan="2">{{Tanggal::tgl_indo($data->tanggal_mulai)." - ".Tanggal::tgl_indo($data->tanggal_selesai)}}</td>
        </tr>
        
        
        <tr>
            <td>Luaran Wajib</td>
            <td>:</td>
            
            <td colspan="2">
                <table width="100%">
                    @foreach($data->luaran_wajib as $no => $wajib)
                    <tr>
                        <td width="3%"> - </td>
                        <td >{{$wajib->nama_luaran}}</td>
                        
                        
                    </tr>
                    @endforeach
                </table>
            </td>
            
        </tr>
        <tr>
            <td>Luaran Tambahan</td>
            <td>:</td>
            
            <td colspan="2">
                <table width="100%">
                    @foreach($data->luaran_tambahan as $no => $wajib)
                    <tr>
                        <td width="3%"> - </td>
                        <td >{{$wajib->nama_luaran}}</td>
                        
                        
                    </tr>
                    @endforeach
                </table>
            </td>
            
        </tr>
        
        <tr>
          <td>Proposal Tanpa Nama</td>
          <td>:</td>
          <td colspan="2">{!!Helpers::cek_dokumen($data->file_proposal_tanpa_nama,'proposal')!!}</td>
        </tr>
        
        @if($data->waktu_ajukan_revisi_proposal!=null)
        <tr>
          <td>Catatan Pebaikan/Revisi Proposal Oleh Pengusul</td>
          <td>:</td>
          <td colspan="2">
            <blockquote>
              {{$data->catatan_revisi_proposal}}
            </blockquote>
          </td>
        </tr>
        <tr>
          <td>Waktu Revisi</td><td>:</td><td colspan="2">{{Tanggal::time_indo($data->waktu_ajukan_revisi_proposal)}}</td>
        </tr>
        @endif
        <tr>
          <td>Upload Uji Turnitin Proposal </td>
          <td>:</td>
          <td colspan="2">
            
              @if(isset($data->file_proposal_turnitin))
              {!!Helpers::cek_dokumen($data->file_proposal_turnitin,'proposal')!!}<br>
              
                <i>Diunggah pada {!!Tanggal::time_indo($data->tgl_upload_proposal_turnitin)!!}</i>
                <button class="btn btn-primary btn-sm" onclick="modal_upload('proposal-turnitin')"><i class="fa fa-edit"></i> Ganti</button> | 
                <button class="btn btn-danger btn-sm" onclick="hapus_laporan('proposal-turnitin')"><i class="fa fa-trash"></i> Hapus</button>
              @else
                <button class="btn btn-primary btn-sm" onclick="modal_upload('proposal-turnitin')"><i class="fa fa-upload"></i> Upload</button>
              @endif
            
          </td>
        </tr>
        @if($data->waktu_ajukan_revisi_hasil!=null)
        <tr>
          <td>Catatan Pebaikan/Revisi Evaluasi Hasil Oleh Pengusul</td>
          <td>:</td>
          <td colspan="2">
            <blockquote>
              {{$data->catatan_revisi_hasil}}
            </blockquote>
          </td>
        </tr>
        <tr>
          <td>Waktu Revisi</td><td>:</td><td colspan="2">{{Tanggal::time_indo($data->waktu_ajukan_revisi_hasil)}}</td>
        </tr>
        @endif

        
        
         
      </table>
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