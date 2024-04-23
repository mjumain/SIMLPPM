<div class="row">
    <div class="col-xs-12">
        <h3> Hasil Review Proposal</h3>
        @if($data->buka_penerimaan->tahun_anggaran->tgl_umum_hasil_review_proposal!=null&&$data->buka_penerimaan->tahun_anggaran->tgl_umum_hasil_review_proposal <= date('Y-m-d'))
        <div class="table-responsive">

            @forelse($data->reviewer_proposal as $rp)
            <table class="table" cellpadding="3" cellspacing="5">
                
                <tr style="background-color: #d1d1e0;">
                    <td>Reviewer ke-{{$rp->pivot->reviewer_ke}}</td>
                    <td width="2%">:</td>
                    <td width="72%">{!!Helpers::nama_gelar($rp).'<br>'!!}
                        <a href="{{url('/data-umum/'.encrypt($rp->id_pegawai))}}">{{$rp->nip}}</a>
                        @if($rp->pivot->status_review=='belum')        
                        <a href="{{url('seleksi-usulan/hapus-reviewer/'.$rp->pivot->id_reviewer_usulan)}}" class="label label-danger" onclick="return confirm('Hapus Reviewer {{Helpers::nama_gelar($rp)}}?')"><i class="glyphicon glyphicon-trash"> </i> Hapus</a>
                        @endif
                    </td>
                </tr>
                
                @if($rp->pivot->status_review=='sudah')
                <tr>
                    <td  width="26%">Status Review</td>
                    <td width="2%">:</td>
                    <td width="72%">
                        {!!Helpers::status_review($rp->pivot->status_review)!!}
                        |
                        
                    </td>
                    
                </tr>
                <tr>
                    <td>Kriteria/Komponen Penilaian</td>
                    <td>:</td>
                    <td>
                        
                        @php
                            $komponen=DB::table('usulan_has_borang as a')
                            ->where('a.usulan_id',$data->id_usulan)
                            ->join('borang as b','a.borang_id','=','b.id_borang')
                            ->join('skor_borang as c','a.skor_borang_id','=','c.id_skor_borang')
                            ->where('b.tahap','proposal')
                            ->where('a.reviewer_id',$rp->id_pegawai)
                            ->get();
                        @endphp
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="2%">No</th>
                                    <th width="50%">Kriteria/Komponen</th>
                                    <th width="10%">Bobot (%)</th>
                                    <th >Skor</th>
                                    <th >Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $total=[0];
                                @endphp
                                @foreach($komponen as $k)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{!!$k->komponen_penilaian!!}</td>
                                    <td>{{$k->bobot}}</td>
                                    <td>{{$k->skor.' ('.$k->keterangan.')'}}</td>
                                    <td>{{$k->nilai}}</td>
                                    @php
                                    $total[]=$k->nilai;
                                    @endphp
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" style="text-align: center;">Total Nilai</td>
                                    <td colspan="4" style="text-align: center;">{{array_sum($total)}}</td>
                                </tr>
                            </tfoot>
                        </table>
                        
                    </td>
                    
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
                <tr>
                    <td>Waktu Review</td>
                    <td>:</td>
                    <td>{{Tanggal::time_indo($rp->pivot->waktu_review)}} <button onclick="batalkan_review('{{$rp->pivot->id_reviewer_usulan}}')" class="btn btn-sm btn-danger"><i>Batalkan Submit Review</i></button></td>
                </tr>
                <tr>
                    <td>Download File review</td>
                    <td>:</td>
                    <td><a class="btn btn-primary btn-sm" href="{{url('review/download-review/'.encrypt($rp->pivot->id_reviewer_usulan))}}">Hasil Review</a></td>
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
            @if($data->buka_penerimaan->tahun_anggaran->tgl_umum_hasil_review_proposal==null)
                Tanggal pengumuman komentar/catatan reviewer proposal masih belum ditentukan
            @else
                Komentar/catatan review proposal dapat anda lihat pada tanggal {!!Tanggal::tgl_indo($data->buka_penerimaan->tahun_anggaran->tgl_umum_hasil_review_proposal)!!}
            @endif
            
        </div>

        @endif


    </div>
    
</div>