<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
class ResetPassCamaru extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset-password-camaru {angkatan} {no_test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reset password camaru menjadi tanggal lagir, angkatan == all untuk semua no_test=all untuk semua';

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
      $angkatan=$this->argument('angkatan');
      $no_test=$this->argument('no_test');


      $ok=DB::table('siakad.camaru');
      if ($angkatan!='all') {
        $ok=$ok->where('angkatan',$angkatan);
      }
      if ($no_test!='all') {
        $ok=$ok->where('no_test',$no_test);
      }
      if ($ok->count()==0) {
        echo "data tidak ditemukan atau parameter salah";
      }else{
        $ok=$ok->get();
        $no=1;
        foreach ($ok as $v) {
          $cek=DB::table('users')->where('username',$v->no_test)->first();
          $password=explode('-',$v->tgl_lahir);
          if ($cek&&is_array($password) ) {
            DB::table('users')->where('username',$v->no_test)
            ->update([
              'password'=>bcrypt($password[2].$password[1].$password[0]),
              'ganti_password'=>1
            ]);
            echo $no."No test = ".$v->no_test." password = ".$password[2].$password[1].$password[0]." ".$v->nama_mahasiswa." updated.\n";
          }
          $no++;
        }
      }
    }
}
