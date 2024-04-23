<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Usulan;
use Auth;

use DataTables;
use App\TahunAnggaran;
use Helpers;
use DB;
use Tanggal;
use Uang;
use App\ReviewerUsulan;
class ReviewerController extends Controller
{
    function __construct()
  {  
    $this->middleware('permission:read-data-review')->only('index','show');
    $this->middleware('permission:update-data-review')->only('prosesReview','saveSkor','saveReview');
    
    
  }
    public function index(Request $request,$jenis)
    {
        if ($request->ajax()) {
            
            $usulan=Usulan::whereNotIn('status',['belum']);
            if ($jenis=='proposal') {
                $usulan=$usulan->whereHas('reviewer_proposal',function($q)use($request){

                    $q->where('id_pegawai',auth()->user()->pegawai->id_pegawai)
                    ->when($request->status_review,function($r)use($request){
                        $r->where('status_review',$request->status_review);
                    });
                });
            }else if ($jenis=='evaluasi-hasil') {
                $usulan=$usulan->whereHas('reviewer_evaluasi_hasil',function($q)use($request){

                    $q->where('id_pegawai',auth()->user()->pegawai->id_pegawai)
                    ->when($request->status_review,function($r)use($request){
                        $r->where('status_review',$request->status_review);
                    });
                });
            }
            $usulan=$usulan->whereHas('buka_penerimaan',function($q)use($request){
                $q->where('tahun_anggaran_id',$request->tahun_anggaran_id);
            })
            ->when($request->jenis_usulan_id,function($q)use($request){
                $q->whereHas('buka_penerimaan',function($r)use($request){
                    $r->where('jenis_usulan_id',$request->jenis_usulan_id);
                });
            })
            ->orderBy('tgl_ajukan')->select('usulan.*');
            

            //hitung jumlah belum sudah review
            
            if ($jenis=='proposal') {
                $cek_belum=Usulan::whereNotIn('status',['belum'])->whereHas('reviewer_proposal',function($q){

                    $q->where('id_pegawai',auth()->user()->pegawai->id_pegawai)->where('status_review','belum');
                })->count();

                $cek_sudah=Usulan::whereNotIn('status',['belum'])->whereHas('reviewer_proposal',function($r){

                    $r->where('id_pegawai',auth()->user()->pegawai->id_pegawai)->where('status_review','sudah');
                })->count();

                $cek_proses=Usulan::whereNotIn('status',['belum'])->whereHas('reviewer_proposal',function($r){

                    $r->where('id_pegawai',auth()->user()->pegawai->id_pegawai)->where('status_review','proses');
                })->count();
            }
            else if ($jenis=='evaluasi-hasil') {
                $cek_belum=Usulan::whereNotIn('status',['belum'])->whereHas('reviewer_evaluasi_hasil',function($q){

                    $q->where('id_pegawai',auth()->user()->pegawai->id_pegawai)->where('status_review','belum');
                })->count();
                $cek_sudah=Usulan::whereNotIn('status',['belum'])->whereHas('reviewer_evaluasi_hasil',function($r){

                    $r->where('id_pegawai',auth()->user()->pegawai->id_pegawai)->where('status_review','sudah');
                })->count();

                $cek_proses=Usulan::whereNotIn('status',['belum'])->whereHas('reviewer_evaluasi_hasil',function($r){

                    $r->where('id_pegawai',auth()->user()->pegawai->id_pegawai)->where('status_review','proses');
                })->count();
            }

            return DataTables::of($usulan)
            ->with([
                'belum_review'=>$cek_belum,
                'sudah_review'=>$cek_sudah,
                'dalam_proses'=>$cek_proses
            ])
            ->addColumn('pendanaan',function($q){
            

                return $q->buka_penerimaan->jenis_usulan->jenis_usulan.' - '.$q->buka_penerimaan->sumber_dana->nama_sumber_dana.' - '.$q->buka_penerimaan->unit_kerja->nama_unit.' - '.$q->buka_penerimaan->skim->nama_skim ;
            })
            
            ->addColumn('status_usulan',function($q){
                    $des=Helpers::status_usulan($q->status);
                    if ($q->waktu_ajukan_revisi_proposal!=null) {
                      $des.="<br>Direvisi pada ".Tanggal::time_indo($q->waktu_ajukan_revisi_proposal);
                    }

                    return $des;
                })
            
            ->addColumn('status_review',function($q)use($jenis){
                $stat=ReviewerUsulan::where('usulan_id',$q->id_usulan)
                ->where('reviewer_id',auth()->user()->pegawai->id_pegawai);
                if ($jenis=='proposal') $stat=$stat->where('tahap','proposal');
                if ($jenis=='evaluasi-hasil') $stat=$stat->where('tahap','evaluasi-hasil'); 
                $stat=$stat->first();
                if ($stat->status_review=='sudah') {
                    
                    return Helpers::status_review($stat->status_review)."<br>"."<a alt='Donwload Hasil Review' href='".url('review/download-review/'.encrypt($stat->id_reviewer_usulan))."' class='btn btn-sm btn-primary'><i class='fa fa-file'></i> <a>";
                }else{
                    return Helpers::status_review($stat->status_review);
                }
            })
            ->addColumn('aksi',function($q)use($jenis){

                return "<a class='btn btn-primary btn-sm' target='_blank' href='".url('review/'.$jenis.'/proses-review/'.encrypt($q->id_usulan))."'><i class='fa fa-pencil'></i> Proses</a>";
            })
            ->escapeColumns('ketua_pengusul')
            ->addIndexColumn()
            ->make(true);
        }

        if ($jenis=='proposal') {
            $data['title']="Review Proposal";
        }elseif ($jenis=='evaluasi-hasil') {
            $data['title']="Review Evaluasi Hasil";
        }
        $data['tahun']=Helpers::tahun_anggaran_aktif();
        $data['jenis']=$jenis;
        return view('admin.reviewer.daftar-usulan-reviewer-page',compact('data'));
    }

     public function prosesReview($jenis,$id)
     {
        $data=Usulan::find(decrypt($id));
        if ($jenis=='proposal') {
            $header['judul']="Review Proposal";
            $borang=$data->jenis_skema->borang_proposal;
            $reviewer_usulan=ReviewerUsulan::where('tahap','proposal')
                            ->where('usulan_id',$data->id_usulan)
                            ->where('reviewer_id',auth()->user()->pegawai->id_pegawai )
                            ->first();
        }elseif ($jenis=='evaluasi-hasil') {
            $header['judul']="Review Evaluasi Hasil";
            $borang=$data->jenis_skema->borang_evaluasi_hasil;
            $reviewer_usulan=ReviewerUsulan::where('tahap','evaluasi-hasil')
                            ->where('usulan_id',$data->id_usulan)
                            ->where('reviewer_id',auth()->user()->pegawai->id_pegawai )
                            ->first();
        }
        $header['jenis']=$jenis;

        return view('admin.reviewer.proses-review-page',compact('header','data','borang','reviewer_usulan','jenis'));

     }

     public function saveSkor(Request $request)
     {

        if ($request->skor_borang_id==0) {
            $cek=DB::table('usulan_has_borang')
                    ->where('usulan_id',$request->usulan_id)
                    ->where('borang_id',$request->borang_id)
                    ->where('reviewer_id',$request->reviewer_id)
                    ->delete();
            $data['nilai']=0;
        }else{

            $cek=DB::table('usulan_has_borang')
                    ->where('usulan_id',$request->usulan_id)
                    ->where('borang_id',$request->borang_id)
                    ->where('reviewer_id',$request->reviewer_id)
                    ->first();
            if ($cek) {

                $cek=DB::table('usulan_has_borang')
                    ->where('usulan_id',$request->usulan_id)
                    ->where('borang_id',$request->borang_id)
                    ->where('reviewer_id',$request->reviewer_id)
                    ->update(['skor_borang_id'=>$request->skor_borang_id]);

            }else{
                $cek=DB::table('usulan_has_borang')->insert([
                    'usulan_id'=>$request->usulan_id,
                    'borang_id'=>$request->borang_id,
                    'reviewer_id'=>$request->reviewer_id,
                    'skor_borang_id'=>$request->skor_borang_id,
                ]);
            }

            $a=DB::table('usulan_has_borang as a')
                    ->where('a.usulan_id',$request->usulan_id)
                    ->where('a.borang_id',$request->borang_id)
                    ->where('a.skor_borang_id',$request->skor_borang_id)
                    ->where('a.reviewer_id',$request->reviewer_id)
                    ->join('skor_borang as b','b.id_skor_borang','=','a.skor_borang_id')
                    ->join('borang as c','c.id_borang','=','a.borang_id')
                    ->select('b.skor','c.bobot')
                    ->first();
            $data['nilai']=$a->skor * $a->bobot;
            DB::table('usulan_has_borang as a')
                    ->where('a.usulan_id',$request->usulan_id)
                    ->where('a.borang_id',$request->borang_id)
                    ->where('a.skor_borang_id',$request->skor_borang_id)
                    ->where('a.reviewer_id',$request->reviewer_id)
                    ->update([
                        'nilai'=>$data['nilai']
                    ]);
        }
        //total nilai
        //dd($request->all());
        $b=DB::table('usulan_has_borang as a')
                ->where('a.usulan_id',$request->usulan_id)
                ->where('a.reviewer_id',$request->reviewer_id)
                ->join('borang as c','c.id_borang','=','a.borang_id');
                if ($request->jenis_review=='proposal') {
                    $b=$b->where('tahap','proposal');
                }elseif($request->jenis_review=='evaluasi-hasil')
                {
                    $b=$b->where('tahap','evaluasi-hasil');
                }
                $b=$b->select('a.nilai')
                ->get();
        $data['nilai_total']=0;
        foreach ($b as $v) {
            $data['nilai_total']+=$v->nilai;
        }
        //dd($data['nilai_total']);
        $data['nilai_total']=number_format($data['nilai_total'],2);
        $update_total=DB::table('reviewer_usulan')->where('usulan_id',$request->usulan_id)
        ->where('reviewer_id',$request->reviewer_id);
        if ($request->jenis_review=='proposal') {
            $update_total=$update_total->where('tahap','proposal');
        }elseif($request->jenis_review=='evaluasi-hasil')
        {
            $update_total=$update_total->where('tahap','evaluasi-hasil');
        }
        $update_total=$update_total->update([
            'nilai'=>number_format($data['nilai_total'],2),
        ]);


        return $data;

     }

     public function saveReview(Request $request,$id_usulan)
     {  
        $id_usulan=decrypt($id_usulan);
        if ($request->status_review=='simpan_sementara') {
            $status='proses';
        }else{
            $status='sudah';
        }
        
         $up=DB::table('reviewer_usulan')
         ->where('reviewer_id',auth()->user()->pegawai->id_pegawai)
         ->where('usulan_id',$id_usulan);
         if ($request->jenis_review=='proposal') {
            $up=$up->where('tahap','proposal');
         }elseif($request->jenis_review=='evaluasi-hasil')
         {
            $up=$up->where('tahap','evaluasi-hasil');
         }
         $up=$up->update([
            'komentar'=>$request->komentar,
            'rekomendasi'=>$request->rekomendasi,
            'status_review'=>$status,
            'waktu_review'=>date('Y-m-d H:i:s'),
         ]);

         $usulan=Usulan::find($id_usulan);


         
         
         $nilai_total=0;
         if ($request->jenis_review=='proposal') {
            foreach ($usulan->reviewer_proposal as $n) {
                $nilai_total+=$n->pivot->nilai;
            }
            $usulan->update(['total_nilai_reviewer_proposal'=>$nilai_total]);
         }elseif($request->jenis_review=='evaluasi-hasil')
         {
            foreach ($usulan->reviewer_evaluasi_hasil as $n) {
                $nilai_total+=$n->pivot->nilai;
            }
            $usulan->update(['total_nilai_reviewer_hasil'=>$nilai_total]);
         }

         Helpers::alert('success',"Berhasil simpan review ".str_replace('-','' ,$request->jenis_review)." dengan judul ".$usulan->judul);
         Helpers::log('Menyimpan review  '.str_replace('-',' ' ,$request->jenis_review).' pada usulan '.$usulan->judul);
         return redirect('review/'.$request->jenis_review);


     }

     public function batalkanreview($id)
     {
         $review=ReviewerUsulan::find($id)
                ->update([
                    'status_review'=>'belum',
                    'waktu_review'=>null
                ]);
         $review=ReviewerUsulan::find($id);
         Helpers::log('membatalkan review  '.str_replace('-','' ,$review->tahap).' yang dilakukan oleh '.Helpers::nama_gelar($review->pegawai).' pada usulan '.$review->usulan->judul);
        Helpers::alert('success','Berhasil batalkan review yg sudah disubmit oleh reviewer '.Helpers::nama_gelar($review->pegawai));
        return back();
     }

     public function downloadReview($id)
     {
        
        $data=ReviewerUsulan::find(decrypt($id));
        if ($data->tahap=='proposal'&&$data->usulan->buka_penerimaan->jenis_usulan_id==1) {
            $dir=public_path('/dokumen/temp_surat/review_proposal_penelitian.docx');
            $borang=$data->usulan->borang_penilaian($data->usulan->id_usulan,'proposal',$data->reviewer_id);
            $namafile=$data->usulan->id_usulan.'Review Proposal '.Helpers::nama_gelar($data->usulan->ketua->pegawai).' oleh '.Helpers::nama_gelar($data->pegawai).'.docx';
            $docx = new \PhpOffice\PhpWord\TemplateProcessor($dir);

        }else if ($data->tahap=='proposal'&&$data->usulan->buka_penerimaan->jenis_usulan_id==2) {
            $dir=public_path('/dokumen/temp_surat/review_proposal_pengabdian.docx');
            $borang=$data->usulan->borang_penilaian($data->usulan->id_usulan,'proposal',$data->reviewer_id);
            $namafile=$data->usulan->id_usulan.' Review Proposal '.Helpers::nama_gelar($data->usulan->ketua->pegawai).' oleh '.Helpers::nama_gelar($data->pegawai).'.docx';
            $docx = new \PhpOffice\PhpWord\TemplateProcessor($dir);
        
        }else if ($data->tahap=='evaluasi-hasil'&&$data->usulan->buka_penerimaan->jenis_usulan_id==1) {
            $dir=public_path('/dokumen/temp_surat/review_monev_penelitian.docx');
            $borang=$data->usulan->borang_penilaian($data->usulan->id_usulan,'evaluasi-hasil',$data->reviewer_id);
            $namafile=$data->usulan->id_usulan.' Review Monev '.Helpers::nama_gelar($data->usulan->ketua->pegawai).' oleh '.Helpers::nama_gelar($data->pegawai).'.docx';
            $docx = new \PhpOffice\PhpWord\TemplateProcessor($dir);
            $docx->setValue('tahun',$data->usulan->buka_penerimaan->tahun_anggaran->tahun);
        }else if ($data->tahap=='evaluasi-hasil'&&$data->usulan->buka_penerimaan->jenis_usulan_id==2) {
            $dir=public_path('/dokumen/temp_surat/review_monev_pengabdian.docx');
            $borang=$data->usulan->borang_penilaian($data->usulan->id_usulan,'evaluasi-hasil',$data->reviewer_id);
            $namafile=$data->usulan->id_usulan.' Review Monev '.Helpers::nama_gelar($data->usulan->ketua->pegawai).' oleh '.Helpers::nama_gelar($data->pegawai).'.docx';
            $docx = new \PhpOffice\PhpWord\TemplateProcessor($dir);        
        }

        
        $docx->setValue('judul',htmlentities(strtoupper($data->usulan->judul)));
        $docx->setValue('bidang',htmlentities($data->usulan->tema->bidang->nama_bidang));
        $docx->setValue('unit_kerja',htmlentities(ucwords(strtolower($data->usulan->buka_penerimaan->unit_kerja->nama_unit))));
        $docx->setValue('nama_lengkap',Helpers::nama_gelar($data->usulan->ketua->pegawai));
        $docx->setValue('nidn',$data->usulan->ketua->pegawai->nip);
        
        $docx->setValue('jumlah_anggota',$data->usulan->anggota->count());
        $docx->setValue('lama_kegiatan',Tanggal::selisih_bulan($data->usulan->tanggal_mulai,$data->usulan->tanggal_selesai));
        $docx->setValue('dana',Uang::format_uang($data->usulan->dana_perjudul));
        $jumborang=count($borang);
        $docx->cloneRow('no', $jumborang);
        $i=1;
        $jskor=0;
        $jnilai=0;
        foreach ($borang as $a => $val) {
            $docx->setValue('no#'.$i,$i);
            $docx->setValue('komponen#'.$i,htmlentities(strip_tags($val->komponen_penilaian)));
            $docx->setValue('bobot#'.$i,$val->bobot);
            $docx->setValue('skor#'.$i,$val->skor);
            $docx->setValue('nilai#'.$i,$val->nilai);
            $jskor=$jskor+$val->skor;
            $jnilai=$jnilai+$val->nilai;
           
        $i++;
        }
        $docx->setValue('jskor',$jskor);
        $docx->setValue('jnilai',$jnilai);
        $docx->setValue('komentar',htmlentities($data->komentar));
        $docx->setValue('rekomendasi',$data->rekomendasi);
        $docx->setValue('tanggal',Tanggal::tgl_indo(date('Y-m-d')));
        $docx->setValue('nama_reviewer',Helpers::nama_gelar($data->pegawai));
        $docx->setValue('nip',$data->pegawai->nip);
        
        $path=public_path()."/dokumen/reviewer/".$namafile;
        $docx->saveAs($path);
        Helpers::log("mendownload  hasil review ".$namafile);
        return response()->download($path);

     }
}
