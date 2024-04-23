<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
class UpdateMasterSekolaha extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-master-sekolah-dari-satu-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memperbaharui master data sekolah yang bersumber dari api kemendikbud ke table sekolah';

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
      $prop=DB::table('wilayah')->where('id_level_wilayah',1)->get();
      $bentuk=['SMA','SMK','MA','MAK','Paket C','Satap SD SMP SMA','SPK SMA','Pondok Pesantren','SMAg.K','SMAK'];
      $no=1;
      $no2=1;
      foreach ($prop as $prop) {
            
        echo "\n\n".$no.' - Memulai proses get sekolah dari provinsi '.$prop->id_wilayah.' - '.$prop->nama_wilayah."\n";    
        $jumlah_sekolah=DB::table('sekolah')->where('kode_provinsi',$prop->id_wilayah)->count();
        echo "    Jumlah sekolah = ".$jumlah_sekolah."\n";
        $jumlah_insert=0;
        $jumlah_update=0;
        for ($i=0;$i<count($bentuk);$i++) {
            
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, "http://jendela.data.kemdikbud.go.id/api/index.php/Csekolah/detailSekolahGET?mst_kode_wilayah=".$prop->id_wilayah."&bentuk=".$bentuk[$i]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
            $sekolah = curl_exec($ch); 
            curl_close($ch);
            $dataSekolah['data']=[];
            $dataSekolah=json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '',$sekolah),true);
            if (is_array($dataSekolah)) {
              if (is_array($dataSekolah['data'])) {
                # code...
              
                if (count($dataSekolah['data']) > 0 ) {
                    foreach ($dataSekolah['data'] as $sekolah) {
                               
                        $cek=DB::table('sekolah')->where('npsn_sekolah',$sekolah['npsn'])->first();
                        if (!$cek) {
                            DB::table('sekolah')->insert([
                                'npsn_sekolah'=>$sekolah['npsn'],
                                'kode_provinsi'=>$sekolah['kode_prop'],
                                'kode_kab_kota'=>$sekolah['kode_kab_kota'],
                                'kode_kecamatan'=>$sekolah['kode_kec'],
                                'nama_sekolah'=>$sekolah['sekolah'],
                                'bentuk'=>$sekolah['bentuk'],
                                'status'=>$sekolah['status'],
                                'alamat_jalan'=>$sekolah['alamat_jalan'],
                                'lintang'=>$sekolah['lintang'],
                                'bujur'=>$sekolah['bujur'],
                                'uuid'=>$sekolah['id'],
                            ]);
                            $jumlah_insert++;
                            echo "     ".$no2.' - '.$bentuk[$i].' '.$sekolah['npsn'].' - '.$sekolah['sekolah']." Inserted! \n";
                        }else{
                            DB::table('sekolah')->where('npsn_sekolah',$sekolah['npsn'])->update([
                                'npsn_sekolah'=>$sekolah['npsn'],
                                'kode_provinsi'=>$sekolah['kode_prop'],
                                'kode_kab_kota'=>$sekolah['kode_kab_kota'],
                                'kode_kecamatan'=>$sekolah['kode_kec'],
                                'nama_sekolah'=>$sekolah['sekolah'],
                                'bentuk'=>$sekolah['bentuk'],
                                'status'=>$sekolah['status'],
                                'alamat_jalan'=>$sekolah['alamat_jalan'],
                                'lintang'=>$sekolah['lintang'],
                                'bujur'=>$sekolah['bujur'],
                                'uuid'=>$sekolah['id'],
                            ]);
                            $jumlah_update++;
                            echo "     ".$no2.' - '.$bentuk[$i].' '.$sekolah['npsn'].' - '.$sekolah['sekolah']." Updated! \n";
                        }

                        $no2++;  
                    }
                }
              }else{
                echo "<pre>";
                var_dump($dataSekolah['data']);
                echo "</pre>";
                break;
              }
            }else{
              echo "<pre>";
              var_dump($dataSekolah);
              echo "</pre>";
              break;
            }
              
                 
        }
        echo "    Jumlah sekolah insert= ".$jumlah_insert."\n";
        echo "    Jumlah sekolah update= ".$jumlah_update."\n";
        echo "    Jumlah Sekolah Sekarang = ". DB::table('sekolah')->where('kode_provinsi',$prop->id_wilayah)->count();;
        echo "\n\n ========================================================= \n\n";
       
        $no++;
            
      }
    }
}
