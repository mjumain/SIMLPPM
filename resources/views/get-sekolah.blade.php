<!DOCTYPE html>
<html>
<head>
  <title>Get sekolah</title>
</head>
<body>
  @php
      $prop=DB::table('wilayah')->where('id_level_wilayah',1)->where('id_wilayah','010000')->get();
      $bentuk=['SMA','SMK','MA','MAK','Paket C','Satap SD SMP SMA','SPK SMA','Pondok Pesantren','SMAg.K','SMAK'];
      $no=1;
      $no2=1;
  @endphp
  <table border="1" cellpadding="3" cellspacing="2">
    <tr>
      <td>Kabupaten</td>
      
      <td>Sekolah</td>
    </tr>
      

    @foreach ($prop as $prop)    
      <tr>
        <td>{!!$no.' - '.$prop->id_wilayah.' - '.$prop->nama_wilayah."<br>"!!}</td>
        <td>
          @for ($i=0;$i<count($bentuk);$i++)
            @php
              $ch = curl_init(); 
              curl_setopt($ch, CURLOPT_URL, "http://jendela.data.kemdikbud.go.id/api/index.php/Csekolah/detailSekolahGET?mst_kode_wilayah=".$prop->id_wilayah."&bentuk=".$bentuk[$i]);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
              $sekolah = curl_exec($ch); 
              curl_close($ch);
              $dataSekolah=json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $sekolah),true);
              
            @endphp
            @if(count($dataSekolah['data']) > 0)
              <table>
                @foreach($dataSekolah['data'] as $sekolah)
                  <tr>
                    <td valign="top">{{$loop->iteration}}</td>
                    <td valign="top">{{$sekolah['npsn']}}</td>
                    <td valign="top">{{$sekolah['sekolah']}}</td>
                    @php
                      $cek=DB::table('sekolah')->where('npsn_sekolah',$sekolah['npsn'])->first();
                      if ($cek) {
                        
                      }
                    @endphp
                  </tr>    
                @endforeach
              </table>
            @endif
            
          @endfor
        </td>
      </tr>    
    @php $no++ @endphp  
    @endforeach

  </table>
</body>
</html>