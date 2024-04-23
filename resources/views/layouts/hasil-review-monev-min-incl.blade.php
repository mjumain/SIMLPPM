<div class="row">
    <div class="col-xs-12">
        <h3> Hasil Review Monitoring dan Evaluasi</h3>
        @if($data->buka_penerimaan->tahun_anggaran->tgl_umum_hasil_review_evaluasi_hasil!=null&&$data->buka_penerimaan->tahun_anggaran->tgl_umum_hasil_review_evaluasi_hasil <= date('Y-m-d'))
        <div class="table-responsive">

            @forelse($data->reviewer_evaluasi_hasil as $rp)
            <table class="table" cellpadding="3" cellspacing="5">
                
                <tr>
                    <td colspan="3">Reviewer ke-{{$rp->pivot->reviewer_ke}}</td>
                    
                </tr>
                
                @if($rp->pivot->status_review=='sudah')
                <tr>
                    <td  width="26%">Status Review</td>
                    <td width="2%">:</td>
                    <td width="72%">{!!Helpers::status_review($rp->pivot->status_review)!!}</td>
                    
                </tr>
                
                <tr>
                    <td>Komentar</td>
                    <td>:</td>
                    <td>{!!$rp->pivot->komentar!!}</td>
                    
                </tr>
                <tr>
                    <td>Rekomendasi</td>
                    <td>:</td>
                    <td>{!!$rp->pivot->rekomendasi!!}</td>
                    
                </tr>
                @else
                <tr>
                    <td colspan="3">
                        <div class="alert-info alert">
                            Belum selesai direview
                        </div>
                    </td>
                    
                    
                </tr>
                @endif
            </table>
            <br>
            
            @empty
            <div class="col-lg-12">
                <div class="alert alert-info">
                    Reviewer belum diinput
                </div>
            </div>
            @endforelse
        </div>
        @else
        <div class="alert alert-info">
            @if($data->buka_penerimaan->tahun_anggaran->tgl_umum_hasil_review_evaluasi_hasil==null)
                Tanggal pengumuman komentar/catatan reviewer monitoring dan evaluasi masih belum ditentukan
            @else
                Komentar/catatan review monitoring dan evaluasi dapat anda lihat pada tanggal {!!Tanggal::tgl_indo($data->buka_penerimaan->tahun_anggaran->tgl_umum_hasil_review_evaluasi_hasil)!!}
            @endif
            
        </div>

        @endif


    </div>
    
</div>