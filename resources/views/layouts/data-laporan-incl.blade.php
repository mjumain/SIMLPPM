<div class="row">
  <div class="col-xs-12 col-sm-12">
    
    <div class="table-responsive">

      <table class="table table-striped">

       
       
        <tr>
          <td width="26%">Laporan Kemajuan</td>
          <td width="2%">:</td>
          <td width="72%" colspan="2">{!!Helpers::cek_dokumen($data->file_laporan_kemajuan,'laporan-kemajuan')!!}<br>
            <i>Diunggah pada {!!Tanggal::time_indo($data->tgl_upload_laporan_kemajuan)!!}</i>
          </td>
        </tr>
       
        <tr>
          <td>Laporan Akhir</td>
          <td>:</td>
          <td colspan="2">{!!Helpers::cek_dokumen($data->file_laporan_akhir,'laporan-akhir')!!}<br>
            <i>Diunggah pada {!!Tanggal::time_indo($data->tgl_upload_laporan_akhir)!!}</i>
          </td>
        </tr>
        
       
        <tr>
          <td>Artikel</td>
          <td>:</td>
          <td colspan="2">{!!Helpers::cek_dokumen($data->file_artikel,'artikel')!!}<br>
            <i>Diunggah pada {!!Tanggal::time_indo($data->tgl_upload_artikel)!!}</i>
          </td>
        </tr>
       
        <tr>
          <td>Luaran</td>
          <td>:</td>
          <td colspan="2">{!!Helpers::cek_dokumen($data->file_luaran,'luaran')!!}<br>
            <i>Diunggah pada {!!Tanggal::time_indo($data->tgl_upload_luaran)!!}</i>
          </td>
        </tr>
        
        <tr>
          <td>Link Ke Jurnal/Luaran</td>
          <td>:</td>
          <td colspan="2">
            {{$data->link_jurnal}}
          </td>
        </tr>
        
        
        
        
        
         
      </table>
    </div>
  </div>

</div>