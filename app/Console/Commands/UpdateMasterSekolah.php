<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Wilayah;
use DOMDocument;
class UpdateMasterSekolah extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-master-sekolah';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memperbaharui master data sekolah yang bersumber dari scraping halaman data kemendikbud';

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

   private function clean_string($string) {
      $s = trim($string);
      $s = iconv("UTF-8", "UTF-8//IGNORE", $s); // drop all non utf-8 characters

      // this is some bad utf-8 byte sequence that makes mysql complain - control and formatting i think
      $s = preg_replace('/(?>[\x00-\x1F]|\xC2[\x80-\x9F]|\xE2[\x80-\x8F]{2}|\xE2\x80[\xA4-\xA8]|\xE2\x81[\x9F-\xAF])/', ' ', $s);

      $s = preg_replace('/\s+/', ' ', $s); // reduce all multiple whitespace to a single space

      return $s;
    }
    public function handle()
    {
      $kec=Wilayah::where('id_level_wilayah',3)->where('id_parent_utama','350000')->get();
      
      $no=1;
      $no2=1;
      foreach ($kec as $kecamatan) {
            
        echo "\n\n".$no.' - Memulai proses get madrasah dari kecamatan '.$kecamatan->id_wilayah.' - '.$kecamatan->nama_wilayah."\n";    
        $jumlah_insert=0;
        $jumlah_update=0;
        // do anything you want with your response
        $jenis_sekolah_=[13,15,16,37,39,55];


        foreach ($jenis_sekolah_ as $id_jenis) {
          if ($id_jenis==13) {
             $bentuk='SMA';
           } 
           else if ($id_jenis==15) {
             $bentuk='SMK';
           } 
           else if ($id_jenis==16) {
             $bentuk='MA';
           } 
           else  {
             $bentuk='Lainnya';
           } 
        
          $htmlContent = file_get_contents("https://referensi.data.kemdikbud.go.id/index11.php?level=3&kode=".$kecamatan->id_wilayah."&id=".$id_jenis);
          $htmlContent=preg_replace('/<table width="100%">(.+?)<\/table>/s','',$htmlContent);
            $DOM = new DOMDocument();
            @$DOM->loadHTML($htmlContent);
              
            $Header = $DOM->getElementsByTagName('th');
            $Detail = $DOM->getElementsByTagName('td');
           //#Get header name of the table
            $aDataTableHeaderHTML=[];
            foreach($Header as $NodeHeader) 
            {
              $aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
            }
            //print_r($aDataTableHeaderHTML); die();

            //#Get row data/detail table without header name as key
            $i = 0;
            $j = 0;
            $aDataTableDetailHTML=[];
            foreach($Detail as $sNodeDetail) 
            {
              $aDataTableDetailHTML[$j][] = trim($sNodeDetail->textContent);
              $i = $i + 1;
              $j = $i % count($aDataTableHeaderHTML) == 0 ? $j + 1 : $j;
            }
            if (count($aDataTableDetailHTML) > 0 ) {
              
            
              foreach ($aDataTableDetailHTML as $sekolah) {
                  $npsn=trim($sekolah[1],"\xC2\xA0\n");
                  $cek=DB::table('sekolah')->where('npsn_sekolah',$npsn)->first();
                  $status="UNK"; 
                  if (trim($sekolah[5])=="SWASTA") {
                    $status="S"; 
                  }elseif (trim($sekolah[5])=="NEGERI") {
                    $status="N";
                  }
                  else  {
                    $status="UNKNOW";
                  }
                  if (!isset($cek)) {
                    if ($kecamatan->kabupaten_kota) {
                      DB::table('sekolah')->insert([
                          'npsn_sekolah'=>$npsn,
                          'kode_provinsi'=>$kecamatan->kabupaten_kota->provinsi->id_wilayah,
                          'kode_kab_kota'=>$kecamatan->kabupaten_kota->id_wilayah,
                          'kode_kecamatan'=>$kecamatan->id_wilayah,
                          'nama_sekolah'=>$this->clean_string($sekolah[2]),
                          'bentuk'=>$bentuk,
                          'status'=>$status,
                          'alamat_jalan'=>$this->clean_string($sekolah[3]),
                          
                          'keterangan'=>'scraping',
                      ]);
                      $jumlah_insert++;
                      echo "     ".$no2.' - '.$bentuk.' - '.$npsn.' - '.$sekolah[2]." Inserted! \n";
                    }
                      
                  }else{
                     if ($kecamatan->kabupaten_kota) {
                      DB::table('sekolah')->where('npsn_sekolah',$npsn)->update([
                          'npsn_sekolah'=>$npsn,
                          'kode_provinsi'=>$kecamatan->kabupaten_kota->provinsi->id_wilayah,
                          'kode_kab_kota'=>$kecamatan->kabupaten_kota->id_wilayah,
                          'kode_kecamatan'=>$kecamatan->id_wilayah,
                          'nama_sekolah'=>$this->clean_string($sekolah[2]),
                          'bentuk'=>$bentuk,
                          'status'=>$status,
                          'alamat_jalan'=>$this->clean_string($sekolah[3]),
                          
                          'keterangan'=>'scraping',
                      ]);
                      $jumlah_update++;
                      echo "     ".$no2.' - '.$bentuk.' - '.$npsn.' - '.$sekolah[2]." Updated! \n";
                    }
                  }

                  $no2++;  
              }
            }
          echo "    Jumlah sekolah insert= ".$jumlah_insert."\n";
          echo "    Jumlah sekolah update= ".$jumlah_update."\n";
          //echo "    Jumlah Sekolah Sekarang = ". DB::table('sekolah')->where('kode',$prop->id_wilayah)->count();;
          echo "\n\n ========================================================= \n\n";
         
        }    
        $no++;
      }
    }
}
