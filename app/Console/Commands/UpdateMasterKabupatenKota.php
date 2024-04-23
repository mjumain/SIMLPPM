<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
class UpdateMasterKabupatenKota extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-master-kabupaten-kota';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memperbaharui master data kabupaten kota di indonesia';

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
      $ch = curl_init(); 
      curl_setopt($ch, CURLOPT_URL, "http://jendela.data.kemdikbud.go.id/api/index.php/cwilayah/wilayahKabGet");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
      $res = curl_exec($ch); 
      curl_close($ch);
      
      $dataKabKota=json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '',$res),true);
      $no=1;
      $no2=1;
      $jumlah_insert=0;
      $jumlah_update=0;
      //dd($dataKabKota);

      $db="siakad.wilayah";
      foreach ($dataKabKota['data'] as $k) {
        $kode_wilayah=trim($k['kode_wilayah']);
        $mst_kode_wilayah=trim($k['mst_kode_wilayah']);
            
        echo "\n\n".$no.' - Get kabupaten kota '.$kode_wilayah.' - '.$k['nama']."\n";    
        
        
        $cek=DB::table($db)->where('id_wilayah',$kode_wilayah)->first();
        if (!$cek) {
            DB::table($db)->insert([
                'id_wilayah'=>$kode_wilayah,
                'id_negara'=>"ID",
                'nama_wilayah'=>$k['nama'],
                'id_induk_wilayah'=>$mst_kode_wilayah,
                'id_level_wilayah'=>2,
                'keterangan'=>'from_api'
                
            ]);
            $jumlah_insert++;
            echo "     ".$no.' - '.$kode_wilayah.' '.$k['nama'].' - '.$mst_kode_wilayah." Inserted! \n";
            
        }else{
            DB::table($db)->where('id_wilayah',$kode_wilayah)->update([
                'id_wilayah'=>$kode_wilayah,
                'id_negara'=>"ID",
                'nama_wilayah'=>$k['nama'],
                'id_induk_wilayah'=>$mst_kode_wilayah,
                'id_level_wilayah'=>2,
                'keterangan'=>'from_api'
            ]);
            $jumlah_update++;
             echo "     ".$no.' - '.$kode_wilayah.' '.$k['nama'].' - '.$mst_kode_wilayah." Update! \n";
        }

        
       
        $no++;
            
      }
        echo "    Jumlah kabupaten kota insert= ".$jumlah_insert."\n";
        echo "    Jumlah kabupaten kota update= ".$jumlah_update."\n";
        echo "    Jumlah kabupaten kota Sekarang = ". DB::table($db)->where('id_level_wilayah',2)->count();;
        echo "\n\n ========================================================= \n\n";

    }
}
