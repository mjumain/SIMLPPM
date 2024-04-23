<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
class UpdateKecamatan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-master-kecamatan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memperbaharui master data kecamatan di indonesia';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        $database='wilayah';

        $data=DB::table($database)->where('id_level_wilayah',1)->get();
        $no=1;
        $no2=1;
        $jumlah_insert=0;
        $jumlah_update=0;
        foreach ($data as $key => $prop) {
            
            $id_prop=$prop->id_wilayah;
              
            echo "\n\n".$no.' - Get kecamatan pada provinsi '.$prop->nama_wilayah."\n";    
              $ch = curl_init(); 
              curl_setopt($ch, CURLOPT_URL, "http://jendela.data.kemdikbud.go.id/api/index.php/Csekolah/detailSekolahGET?mst_kode_wilayah=".$id_prop."&bentuk=sd");
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
              $res = curl_exec($ch); 
              curl_close($ch);
              
              $dataApiKecamatan=json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '',$res),true);
              //dd($dataKabKota);
              $id_kec=[];
              foreach ($dataApiKecamatan['data'] as $k) {
                $kode_prop=trim($k['kode_prop']);
                $kode_kab_kota=trim($k['kode_kab_kota']);
                $kode_kec=trim($k['kode_kec']);
                if (!in_array($kode_kec,$id_kec)) {
                    $id_kec[]=$kode_kec;
                
                
                
                
                    $cek=DB::table($database)->where('id_wilayah',$kode_kec)->first();
                    if (!$cek) {
                        //cek kbaupaten kota sudah ada?
                        $cek_kab_kota=DB::table($database)->where('id_wilayah',$kode_kab_kota)->first();
                        if (!$cek_kab_kota) {
                            DB::table($database)->insert([
                              'id_wilayah'=>$kode_kab_kota,
                              'id_negara'=>"ID",
                              'nama_wilayah'=>$k['kabupaten_kota'],
                              'id_induk_wilayah'=>$kode_prop,
                              'id_level_wilayah'=>2,
                              'keterangan'=>'from_api_kecamatan',
                              'id_parent_utama'=>$kode_prop,
                            
                            ]);
                        } 

                        DB::table($database)->insert([
                            'id_wilayah'=>$kode_kec,
                            'id_negara'=>"ID",
                            'nama_wilayah'=>$k['kecamatan'],
                            'id_induk_wilayah'=>$kode_kab_kota,
                            'id_level_wilayah'=>3,
                            'keterangan'=>'from_api_kecamatan',
                            'id_parent_utama'=>$kode_prop,
                            
                        ]);
                        $jumlah_insert++;
                        echo "     ".$no2.' - '.$kode_kec.' '.$k['kecamatan']." Inserted! \n";
                        
                    }else{
                      //cek kbaupaten kota sudah ada?
                        $cek_kab_kota=DB::table($database)->where('id_wilayah',$kode_kab_kota)->first();
                        if (!$cek_kab_kota) {
                            DB::table($database)->insert([
                              'id_wilayah'=>$kode_kab_kota,
                              'id_negara'=>"ID",
                              'nama_wilayah'=>$k['kabupaten_kota'],
                              'id_induk_wilayah'=>$kode_prop,
                              'id_level_wilayah'=>2,
                              'keterangan'=>'from_api_kecamatan',
                              'id_parent_utama'=>$kode_prop,
                            
                            ]);
                        } 
                     
                        DB::table($database)->where('id_wilayah',$kode_kec)->update([
                            'id_wilayah'=>$kode_kec,
                            'id_negara'=>"ID",
                            'nama_wilayah'=>$k['kecamatan'],
                            'id_induk_wilayah'=>$kode_kab_kota,
                            'id_level_wilayah'=>3,
                            'keterangan'=>'from_api_kecamatan',
                            'id_parent_utama'=>$kode_prop,
                        ]);
                        $jumlah_update++;
                        echo "     ".$no2.' - '.$kode_kec.' '.$k['kecamatan']." Updated! \n";
                        
                    }
                    $no2++;
                }
                
               
                    
              }

                echo "    Jumlah kecamatan insert= ".$jumlah_insert."\n";
                echo "    Jumlah kecamatan update= ".$jumlah_update."\n";
                echo "    Jumlah kecamatan Sekarang = ". DB::table($database)->where('id_level_wilayah',3)->count();;
                echo "\n\n ========================================================= \n\n";
            $no++;
        }

    }
}
