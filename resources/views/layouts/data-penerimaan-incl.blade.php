<div class="row">
    <div class="col-xs-12">
        <h3> Informasi Penerimaan</h3>
        <div class="table-responsive">

            <table class="table table-striped">
                <tr>
                    <td width="26%">Tahun</td>
                    <td width="2%">:</td>
                    <td width="72%">{{$data->buka_penerimaan->tahun_anggaran->tahun}}</td>
                    
                </tr>
                <tr>
                    <td>Jenis Usulan</td>
                    <td>:</td>
                    <td>{{$data->buka_penerimaan->jenis_usulan->jenis_usulan}}</td>
                    
                </tr>
                <tr>
                    <td>Sumber Dana</td>
                    <td>:</td>
                    <td>{{$data->buka_penerimaan->sumber_dana->nama_sumber_dana}}</td>
                    
                </tr>
                <tr>
                    <td>Unit Kerja</td>
                    <td>:</td>
                    <td>{{$data->buka_penerimaan->unit_kerja->nama_unit}}</td>
                    
                   
                </tr>
                <tr>
                    <td>Skim / Program</td>
                    <td>:</td>
                    <td>{{$data->buka_penerimaan->skim->nama_skim}}</td>
                     
                   
                </tr>
                <tr>
                    <td>Jumlah dana</td>
                    <td>:</td>
                    <td>Rp. {{Uang::format_uang($data->buka_penerimaan->jumlah_dana)}}
                    

                </tr>
                <tr>
                    <td>Jumlah Judul</td>
                    <td>:</td>
                    <td>{{$data->buka_penerimaan->jumlah_judul." judul"}}</td>
                </tr>
                
                
                
            </table>
        </div>
    </div>
    
</div>