<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Usulan;
use App\JenisUsulan;
use App\Bidang;
use App\BukaPenerimaan;
use Helpers;
use App\Pelaksana;
use File;
use App\JenisSkema;
use App\PelaksanaMahasiswa;
class PendaftaranUsulanController extends Controller
{
  function __construct()
  {  
    $this->middleware('cek-diblok');
    $this->middleware('cek-data-pendukung');
    $this->middleware('permission:read-pendaftaran-usulan')->only('index','detail');
    $this->middleware('permission:create-pendaftaran-usulan')->only('buatUsulan','store','inputAnggota','postInputAnggota');
    $this->middleware('permission:update-pendaftaran-usulan')->only('edit','update','ajukan');
    $this->middleware('permission:delete-pendaftaran-usulan')->only('destroy');
    
  }
   public function index()
   {
   	  
      $usulan=Usulan::whereHas('pelaksana',function($q){
        $q->where('id_peg',auth()->user()->id_peg)->where('konfirmasi','bersedia');

      })
      ->orderBy('tanggal_mulai','desc')
      ->get();
      $buka_penerimaan=BukaPenerimaan::where('jadwal_buka','<=',date('Y-m-d'))
                      ->where('jadwal_tutup','>=',date('Y-m-d'))
                      ->get();
      
      if (count($buka_penerimaan) > 0) {
        $buat_usulan=true;

      }
      else{
       $buat_usulan=false; 
      }                
      return view('admin.pendaftaran-usulan.pendaftaran-usulan-page',compact('usulan','buat_usulan'));
   }
   public function buatUsulan($id_jenis)
   {
    $jenis_usulan=JenisUsulan::find(decrypt($id_jenis));
    $buka_penerimaan=BukaPenerimaan::where('jenis_usulan_id',$jenis_usulan->id_jenis_usulan)
                      ->where('jadwal_buka','<=',date('Y-m-d'))
                      ->where('jadwal_tutup','>=',date('Y-m-d'))
                      ->get();
    $bidang=Bidang::where('jenis_usulan_id',$jenis_usulan->id_jenis_usulan)->where('tahun_anggaran_id',Helpers::tahun_anggaran_aktif()->id_tahun_anggaran)->get();
    
    
    return view('admin.pendaftaran-usulan.create-pendaftaran-usulan-page',compact('jenis_usulan','buka_penerimaan','bidang'));
   }
   public function store(Request $r,$id_jenis)
   {
     $input=$r->except('luaran_wajib_id','luaran_tambahan_id','file_proposal','file_proposal_tanpa_nama','bidang_id');
    $penerimaan=BukaPenerimaan::find($r->buka_penerimaan_id);
     $input['dana_perjudul']=$penerimaan->jumlah_dana/$penerimaan->jumlah_judul;
      
      $cek=Helpers::cek_jumlah_kegiatan($penerimaan->jenis_usulan_id,auth()->user()->id_peg);
    
      if (!in_array($penerimaan->skim_id,[3,4])&&$cek['izin']==false) {
        Helpers::alert('danger',$cek['pesan']);
        return redirect('pendaftaran-usulan');
      } 
     
     if ($r->hasFile('file_proposal')) {
       
      $file=$r->file('file_proposal');
      $input['file_proposal']="proposal-".date('YmdHis').rand(100,999).".".$file->getClientOriginalExtension();
      $pindah=$file->move(public_path()."/dokumen/proposal",$input['file_proposal']);
     }
     if ($r->hasFile('file_proposal_tanpa_nama')) {
       
      $file=$r->file('file_proposal_tanpa_nama');
      $input['file_proposal_tanpa_nama']="proposal-tanpa-nama-".date('YmdHis').rand(100,999).".".$file->getClientOriginalExtension();
      $pindah=$file->move(public_path()."/dokumen/proposal",$input['file_proposal_tanpa_nama']);
     }

      //buat kode usulan 
     $usul=Usulan::orderBy('id_usulan','desc')->first();
     if (!$usul) $urutan=1;
     else $urutan =  (int) substr($usul->kode_usulan,6,6);
     $input['kode_usulan']="KEG".rand(100,999).sprintf("%06s", $urutan+1);

     $insert=Usulan::create($input);
     $insert->luaran_wajib()->sync($r->luaran_wajib_id);
     $insert->luaran_tambahan()->sync($r->luaran_tambahan_id);
     $insert->ketua()->create([
      'id_peg'=>auth()->user()->pegawai->id_peg,
      'jabatan'=>'ketua',
      'konfirmasi'=>'bersedia',
      'tgl_konfirmasi'=>date('Y-m-d H:i:s'),
     ]);
     for ($i=0; $i < $r->jumlah_anggota ; $i++) { 
       Pelaksana::create([
        'usulan_id'=>$insert->id_usulan,
        'jabatan'=>'anggota',
        'konfirmasi'=>'belum',
        
       ]);
     }
     Helpers::log('Membuat usulan dengan judul '.$r->judul);
     return redirect('pendaftaran-usulan/input-anggota/'.encrypt($insert->id_usulan));
   }
   public function inputAnggota($id)
   {
    $data=Usulan::find(decrypt($id));

    return view('admin.pendaftaran-usulan.input-anggota-page',compact('data'));
   }

   public function postInputAnggota(Request $request,$id)
   {
      $id_usulan=decrypt($id);
      
      $id_peg=$request->id_peg;
      if ($request->id_pelaksana) 
      {  
        $id_pelaksana=$request->id_pelaksana;
        //simpan anggota dosen
        for ($i=0; $i < count($id_pelaksana); $i++) { 
          $anggotalama=Pelaksana::where('id_pelaksana',$id_pelaksana[$i])->where('jabatan','anggota')->where('usulan_id',$id_usulan)->first();
          
          if ($anggotalama->id_peg!=$id_peg[$i]) {
            if ($id_peg[$i]==''||$id_peg[$i]==Null) {
              Pelaksana::where('id_pelaksana',$id_pelaksana[$i])->update(['id_peg'=>null,'konfirmasi'=>'belum','tgl_konfirmasi'=>null]);
            }else{
              Pelaksana::where('id_pelaksana',$id_pelaksana[$i])->update(['id_peg'=>$id_peg[$i],'konfirmasi'=>'menunggu','tgl_konfirmasi'=>null]);
            }
          } 
        }
      }

      $data=Usulan::find($id_usulan);
      //dd($request->all());
      $anggotalama=PelaksanaMahasiswa::where('usulan_id',$data->id_usulan)->delete();
      if ($request->nim) {
        for ($i=0; $i < count($request->nim); $i++) { 
           PelaksanaMahasiswa::create([
            'usulan_id'=>$data->id_usulan,
            'nim'=>$request['nim'][$i],
            'nama_mahasiswa'=>$request['nama_mahasiswa'][$i],
            'prodi_id'=>$request['prodi_id'][$i],
           ]);
         
        }
        
      }
      Helpers::log('Menambah anggota usulan '.$data->judul);
      Helpers::alert('success',"Berhasil input anggota kegiatan");
      return back();
   }
   public function edit($id,$is_admin=null)
   {  
      $data=Usulan::find(decrypt($id));
      $jenis_usulan=$data->buka_penerimaan->jenis_usulan;
      $bidang=Bidang::all();
      session(['admin' => false]);
      if ($is_admin=='edit-by-admin') {
        session(['admin' => true]);
      }
      
      return view('admin.pendaftaran-usulan.edit-pendaftaran-usulan-page',compact('data','jenis_usulan','bidang'));
   }
   public function update(Request $r, $id)
   {

    $data=Usulan::find(decrypt($id));
    $input=$r->except('proses_pengajuan','luaran_wajib_id','luaran_tambahan_id','file_proposal','file_proposal_tanpa_nama','bidang_id','id_peg','id_peg_anggota','id_pelaksana');
     
     $input['dana_perjudul']=$data->buka_penerimaan->jumlah_dana/$data->buka_penerimaan->jumlah_judul;
     
     if ($r->hasFile('file_proposal')) {
      $file_lama=File::delete('dokumen/proposal/'.$data->file_proposal); 
      $file=$r->file('file_proposal');
      $input['file_proposal']="proposal-".date('YmdHis').rand(100,999).".".$file->getClientOriginalExtension();
      $pindah=$file->move(public_path()."/dokumen/proposal",$input['file_proposal']);
     }
     if ($r->hasFile('file_proposal_tanpa_nama')) {
      $file_lama=File::delete('dokumen/proposal/'.$data->file_proposal_tanpa_nama); 
      $file=$r->file('file_proposal_tanpa_nama');
      $input['file_proposal_tanpa_nama']="proposal-tanpa-nama-".date('YmdHis').rand(100,999).".".$file->getClientOriginalExtension();
      $pindah=$file->move(public_path()."/dokumen/proposal",$input['file_proposal_tanpa_nama']);
     }

      
     
     if ($r->has('proses_pengajuan')&&isset($r->proses_pengajuan)) {
        $input['status']='sedang_diajukan';
        $input['waktu_ajukan_revisi_proposal']=date('Y-m-d H:i:s');
        File::delete(public_path('dokumen/proposal/'.$data->file_proposal_turnitin));
        $input['file_proposal_turnitin']=null;
        $input['tgl_upload_proposal_turnitin']=null;
        $usul=Usulan::find(decrypt($id));
        foreach ($usul->reviewer_proposal as $z) {
          $z->pivot->status_review='proses';
          $z->push();
        }
        Helpers::alert('success','Berhasil update data dan langsung mengajukan kembali usulan');
     }else{
        Helpers::alert('success','Berhasil update data');
     }  
     $data->update($input);
     $usulan=Usulan::find(decrypt($id));
     $usulan->luaran_wajib()->sync($r->luaran_wajib_id);
     $usulan->luaran_tambahan()->sync($r->luaran_tambahan_id);
      if (session('admin')==true) {
        $data->ketua->id_peg=$r->id_peg;
        $data->push();

        $id_pelaksana=$r->id_pelaksana;
        $id_peg_anggota=$r->id_peg_anggota;
        //simpan anggota dosen
        for ($i=0; $i < count($id_pelaksana); $i++) { 
          $anggotalama=Pelaksana::where('id_pelaksana',$id_pelaksana[$i])->first();
          
          if ($anggotalama->id_peg!=$id_peg_anggota[$i]) {
            
            Pelaksana::where('id_pelaksana',$id_pelaksana[$i])->update(['id_peg'=>$id_peg_anggota[$i],'konfirmasi'=>'bersedia','tgl_konfirmasi'=>date('Y-m-d H:i:s')]);
            
          } 
        }
        $usulan=Usulan::find(decrypt($id));
        Helpers::alert('success',"Berhasil simpan perubahan");
        Helpers::log('Merubah atau edit usulan '.$usulan->judul);
        return redirect('seleksi-usulan/'.$id);

      }
    return redirect('pendaftaran-usulan');
   }
   public function destroy($id)
   {
    $usulan=Usulan::find($id);

    Helpers::alert('success','Berhasil hapus usulan '.$usulan->judul);
    Helpers::log('Menghapus usulan '.$usulan->judul);
    $usulan->delete();
    return back();
   }

   public function detail($id)
   {
    $data=Usulan::find(decrypt($id));
    return view('admin.pendaftaran-usulan.detail-usulan-page',compact('data'));
   }
   public function ajukan($id)
   {
    $data=Usulan::find($id);
    //cek anggota
    if (in_array($data->status,['sedang_diajukan','diterima','ditolak'])) {
      Helpers::alert('danger','Tidak dapat ajukan usulan kerena usulan sudah '.strtoupper(str_replace('_',' ',$data->status)) );
      return back();
    }

    foreach ($data->anggota as $anggota) {
     if (!$anggota->pegawai) {
         Helpers::alert('danger','Tidak dapat ajukan usulan kerena belum isi anggota');
        return back();
      }else{
        if ($anggota->konfirmasi=='menunggu') {
          Helpers::alert('danger','Tidak dapat ajukan usulan kerena anggota kegiatan sdr/i '.Helpers::nama_gelar($anggota->pegawai).' belum mengkonfirmasi keikutsetaan' );
          return back();
        }else if ($anggota->konfirmasi=='menolak') {
          Helpers::alert('danger','Tidak dapat ajukan usulan kerena anggota kegiatan sdr/i '.Helpers::nama_gelar($anggota->pegawai).' menolak keikutsetaan, harap diganti dengan anggota dosen lain' );
          return back();
        }
      } 
    }

    if ($data->buka_penerimaan->jadwal_tutup < date('Y-m-d') &&$date->status=!'revisi') {
      Helpers::alert('danger','Maaf tidak dapat ajukan usulan karena pengajuan sudah ditutup pada tanggal '.Tanggal::tgl_indo($data->buka_penerimaan->jadwal_tutup));
      return back();
    }
    $usul=Usulan::find($id);
    if ($usul->status=='revisi') {
      
      foreach ($usul->reviewer_proposal as $z) {
        $z->pivot->status_review='proses';
        $z->push();
      }
      File::delete(public_path('dokumen/proposal/'.$usul->file_proposal_turnitin));
      $usul->file_proposal_turnitin=null;
      $usul->tgl_upload_proposal_turnitin=null;
    }
    Usulan::find($id)->update([
      'tgl_ajukan'=>date('Y-m-d H:i:s'),
      'status'=>'sedang_diajukan',
    ]);




    Helpers::log('Mengajukan usulan '.$data->judul);
    Helpers::alert('success','berhasil ajukan usulan dengan judul '.$data->judul );
    return back();
   }
}
