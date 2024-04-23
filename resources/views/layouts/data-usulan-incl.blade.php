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
          <td>Ketua</td>
          <td>:</td>
          <td colspan="2"><a href="{{url('data-umum/'.encrypt($data->ketua->pegawai->id_pegawai))}}">   {{Helpers::nama_gelar($data->ketua->pegawai)}}<br>
            {{$data->ketua->pegawai->nip}}</a>
          </td>
        </tr>
        <tr>
          <td>ID Sinta Ketua</td>
          <td>:</td>
          <td colspan="2">{{$data->ketua->id_sinta}}</td>
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
            <td>Jumlah Anggota</td>
            <td>:</td>
            <td>{{$data->jumlah_anggota}} Orang</td>
            <td>
                <table width="100%">
                    <tr>
                      <td colspan="3"><b>Anggota Dosen</b></td>
                    </tr>
                    @foreach($data->anggota as $no => $anggota)
                    <tr valign="top">
                        <td  width="3%">{{$no+1}}. </td>
                        <td>
                          @if($anggota->pegawai)
                          <a href="{{url('data-umum/'.encrypt($anggota->id_peg))}}">
                            {{Helpers::nama_gelar($anggota->pegawai)}}<br>
                            {{$anggota->pegawai->nip}}
                          </a>
                          @else
                            Belum isi anggota
                          @endif
                        </td>
                        
                        <td width="30%">Konfirmasi : {!!Helpers::konfirmasi($anggota)!!}</td>
                    </tr>
                    @endforeach
                </table>
                <br>
                <br>
                <table width="100%">
                    <tr>
                      <td colspan="3"><b>Anggota Mahasiswa</b></td>
                    </tr>

                    @forelse($data->pelaksana_mahasiswa as $no => $anggota)
                    <tr valign="top">
                        <td  width="3%">{{$no+1}}. </td>
                        <td>
                            <u>{{$anggota->nama_mahasiswa}}</u><br>
                            {{$anggota->nim}}
                        </td>
                        <td>
                          Program Studi {{$anggota->prodi->nama_prodi}}
                        </td>
                        
                        
                    </tr>
                    @empty
                    <tr>
                      <td colspan="3">Tidak ada anggota mahasiswa</td>
                    </tr>
                    @endforelse
                </table>
            </td>
            
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
          <td>Proposal</td>
          <td>:</td>
          <td colspan="2">{!!Helpers::cek_dokumen($data->file_proposal,'proposal')!!}</td>
        </tr>
        <tr>
          <td>Proposal Tanpa Nama</td>
          <td>:</td>
          <td colspan="2">{!!Helpers::cek_dokumen($data->file_proposal_tanpa_nama,'proposal')!!}</td>
        </tr>
        <tr>
          <td>Proposal Hasil Uji Turnitin</td>
          <td>:</td>
          <td colspan="2">{!!Helpers::cek_dokumen($data->file_proposal_turnitin,'proposal')!!}</td>
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