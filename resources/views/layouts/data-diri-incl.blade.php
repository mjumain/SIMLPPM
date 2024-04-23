<h2>Data Dosen/Pegawai</h2> 
<div class="table-responsive">
    <table class="table table-striped">

        <tr>
            <td width="20%">Nama</td><td width="2%">:</td><td  width="68%"> {{Helpers::nama_gelar($data)}}</td>
            <td rowspan="5" width="10%"><img src="https://simpeg.umjambi.ac.id/assets/upload/image/{{($data->gambar!="") ? $data->gambar:'no-foto-male.png' }}" width="100" height="130"></td>
        </tr>
        <tr>
            <td >NIP/NIDN/NIDK</td><td>:</td><td> {{$data->nip}}</td>
        </tr>
        <tr>
            <td >Tempat, Tanggal Lahir</td><td>:</td><td> {{$data->tempat_lahir.", ".Tanggal::tgl_indo($data->tanggal_lahir)}}</td>
        </tr>
        <tr>
            <td >Jenis Kelamin</td><td>:</td><td> {{$data->jenis_kelamin=='L' ? 'Laki-laki':'Perempuan'}}</td>
        </tr>
        <tr>
            <td >Agama</td><td>:</td><td> {{ucwords($data->agama)}}</td>
        </tr>
        {{-- <tr>
            <td width="40%">Jabatan Fungsional</td><td>:</td><td colspan="2"> {{$data->jabatan}}</td>
        </tr> --}}

        
        <tr>
            <td>Unit Kerja</td><td>:</td><td colspan="2"> {{$data->unit_kepeg}}</td>
        </tr>
        {{-- <tr>
            <td width="40%">Status Kerja</td><td>:</td><td colspan="2"> {{$data->status_kerja}}</td>
        </tr> --}}
        <tr>
            <td>Alamat</td><td>:</td><td colspan="2"> {{$data->alamat}}</td>
        </tr>
        <tr>
            <td>No HP</td><td>:</td><td colspan="2"> {{$data->telepon}}</td>
        </tr>
        <tr>
            <td>E-mail</td><td>:</td><td colspan="2"> {{$data->user->email}}</td>
        </tr>
        

    </table>
</div>
<br>
<h2>Data Pendukung</h2> 
<div class="table-responsive">
    <table class="table table-striped">

        <tr>
            <td width="20%">Nomor Rekening</td><td width="2%">:</td><td colspan="2"> {{$data->data_pendukung ? $data->data_pendukung->no_rek:"Belum isi"}}</td>
        </tr>
        <tr>
            <td>Nama Direkening</td><td>:</td><td colspan="2"> {{$data->data_pendukung ? $data->data_pendukung->nama_direkening:"Belum isi"}}</td>
        </tr>
        <tr>
            <td>Bank</td><td>:</td><td colspan="2"> {{$data->data_pendukung ? $data->data_pendukung->bank->nama_bank:"Belum isi"}}</td>
        </tr>
         <tr>
            <td>Scan Rekening</td><td>:</td><td colspan="2"> <a href="{{url('/dokumen/rekening/')}}/{{($data->data_pendukung) ? $data->data_pendukung->scan_rekening:""}}" download="">{{$data->data_pendukung ? $data->data_pendukung->scan_rekening:''}}</a> </td>
        </tr>
        
        <tr>
            <td>Nomor NPWP</td><td>:</td><td colspan="2"> {{$data->data_pendukung ? $data->data_pendukung->no_npwp:''}}</td>
        </tr>
        <tr>
            <td>Scan NPWP</td><td>:</td><td colspan="2"> <a href="{{url('/dokumen/npwp/')}}/{{$data->data_pendukung ? $data->data_pendukung->scan_npwp:''}}" download="">{{$data->data_pendukung ? $data->data_pendukung->scan_npwp:''}}</a> </td>
        </tr>

    </table>
</div>