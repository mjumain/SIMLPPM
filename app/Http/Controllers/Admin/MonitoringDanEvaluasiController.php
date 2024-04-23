<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TahunAnggaran;
use App\Unitkerja;
use App\SumberDana;
use App\JenisUsulan;
use App\Usulan;
use App\JenisSkema;
use App\Skim;
use App\ReviewerUsulan;
use DB;
use DataTables;
use Helpers;
use Excel;
use Tanggal;
class MonitoringDanEvaluasiController extends Controller
{
    function __construct()
    {  
      $this->middleware('permission:read-monev')->only('index','show');

      $this->middleware('permission:input-reviewer-monev')->only('index','show','storeReviewerMonev');
      $this->middleware('permission:delete-reviewer-monev')->only('destroyReviewer');
      
    }
    private function dataUsulan($request)
    {

        $data=Usulan::whereIn('usulan.status',['diterima'])
                ->when($request->tahun_anggaran_id,function($q) use($request){
                    $q->whereHas('buka_penerimaan',function($r)use($request){
                      $r->where('tahun_anggaran_id',$request->tahun_anggaran_id);
                    });
                })
                ->when($request->sumber_dana_id,function($q) use($request){
                    $q->whereHas('buka_penerimaan',function($r)use($request){
                      $r->where('sumber_dana_id',$request->sumbe);
                    });
                })
                ->when($request->jenis_usulan_id,function($q) use($request){
                    $q->whereHas('buka_penerimaan',function($r)use($request){
                      $r->where('jenis_usulan_id',$request->jenis_usulan_id);
                    });
                })
                ->when($request->unit_kerja_id,function($q) use($request){
                    $q->whereHas('buka_penerimaan',function($r)use($request){
                      $r->where('unit_kerja_id',$request->unit_kerja_id);
                    });
                })
                ->when($request->skim_id,function($q) use($request){
                    $q->whereHas('buka_penerimaan',function($r)use($request){
                      $r->where('skim_id',$request->unit_kerja_id);
                    });
                })
                ->when($request->jenis_skema_id,function($q) use($request){  
                    $r->where('jenis_skema_id',$request->jenis_skema_id); 
                })
                // ->when($request->status_usulan,function($q) use($request){
                //   if($request->status_usulan=='ajukan_revisi'){
                     
                //       $q->where('usulan.status','diajukan')->whereNotNull('waktu_ajukan_revisi_proposal');
                //   }
                //   else{
                     
                //       $q->where('usulan.status',$request->status_usulan);
                //   }
                    
                // })
                ->when($request->status_review,function($q) use($request){
                  if($request->status_review=='belum_input'){
                     $q->where(function($r){

                        $r->doesntHave('reviewer1_evaluasi_hasil')->doesntHave('reviewer2_evaluasi_hasil','or');
                     });
                  }
                  else if($request->status_review=='belum_review'){
                     $q->whereHas('reviewer1_evaluasi_hasil',function($r){
                      $r->where('status_review','belum');
                     })->orWhereHas('reviewer2_evaluasi_hasil',function($r){
                      $r->where('status_review','belum');
                     });
                  }
                  else if($request->status_review=='sudah_review'){
                     $q->whereHas('reviewer1_evaluasi_hasil',function($r){
                      $r->where('status_review','sudah');
                     })->whereHas('reviewer2_evaluasi_hasil',function($r){
                      $r->where('status_review','sudah');
                     });
                  }
                    
                });
                
        return $data;
    }
    public function index(Request $request)
    {
      
      
        $taaktif=Helpers::tahun_anggaran_aktif();
        if ($request->ajax()) {
            $data=$this->dataUsulan($request)->select('usulan.*')->orderBy('usulan.total_nilai_reviewer_hasil','desc');
            $dt=DataTables::of($data)
                // ->addColumn('ceklis',function($q){
                //   return "<input type='checkbox' class='pilih' name='id_usulan[]' value='".$q->id_usulan."' >";
              
                // })
                ->addColumn('judul_link',function($ret){
                    return "<a href='".url('monitoring-dan-evaluasi/'.encrypt($ret->id_usulan))."' target=\"_blank\"><u>".$ret->judul."</u></a>";
                })
                ->addColumn('pelaksana',function($ret){
                    $html="<table>";
                        foreach ($ret->pelaksana as $key => $value) {
                            $no=$key+1;
                          
                          	if(!$value->pegawai){
                              $html.="Pegawai dengan id ".$value->id_peg." tidak ditemukan";
                            }else{
                            	$a= "<a href='".url('data-umum/'.encrypt($value->id_peg))."' target=\"_blank\"><u>".Helpers::nama_gelar($value->pegawai)."</u><br>".$value->pegawai->nip."</a>";
                            	if ($value->jabatan=='ketua') {
                              		$a.=" <i class='fa fa-id-badge'></i> ";
                            	}
                            	$html.="<tr><td valign='top'>". $no .".&nbsp;&nbsp; </td><td>".$a."</td></tr>";
                            }
                         }
                    $html.="</table>";
                    return $html;

                })
                
                ->addColumn('penerimaan',function($ret){
                    return $ret->buka_penerimaan->jenis_usulan->jenis_usulan ." - ".$ret->buka_penerimaan->unit_kerja->nama_unit.' - '.$ret->buka_penerimaan->skim->nama_skim;
                })
                 ->addColumn('jenis_skema',function($ret){
                    return $ret->jenis_skema->nama_jenis_skema;
                })
                 ->addColumn('r1',function($ret){
                    if (!$ret->reviewer1_evaluasi_hasil) {
                        $button="<button type=\"button\" type class=\"btn btn-primary btn-sm\" onclick=\"tambah_reviewer_monev('".$ret->id_usulan."','1')\"><i class=\"glyphicon glyphicon-plus\"></i> Input Reviewer</button>";
                        return $button;
                    }
                    
                    
                    if ($ret->reviewer1_evaluasi_hasil->status_review=='sudah') {
                        $nilai=$ret->reviewer1_evaluasi_hasil->nilai."<br> <a alt='Download Hasil Review' href='".url('review/download-review/'.encrypt($ret->reviewer1_evaluasi_hasil->id_reviewer_usulan))."' class='btn btn-sm btn-primary'><i class='fa fa-file'></i> <a>";
                    }elseif ($ret->reviewer1_evaluasi_hasil->status_review=='proses') {
                        $nilai=Helpers::nama_gelar($ret->reviewer1_evaluasi_hasil->pegawai)."<br><span class='label label-primary'>Sedang direview</span>";
                    }elseif ($ret->reviewer1_evaluasi_hasil->status_review=='belum') {
                        $button="<br><label class=\"label label-primary\" onclick=\"tambah_reviewer_monev('".$ret->id_usulan."','1')\"><i class=\"glyphicon glyphicon-edit\"></i></label>";
                        $button.=" | <label class=\"label label-danger\" onclick=\"hapus_reviewer_monev('".$ret->reviewer1_evaluasi_hasil->id_reviewer_usulan."','".Helpers::nama_gelar($ret->reviewer1_evaluasi_hasil->pegawai)."','".$ret->judul."')\"><i class=\"glyphicon glyphicon-trash\"></i></label>";
                        $nilai=Helpers::nama_gelar($ret->reviewer1_evaluasi_hasil->pegawai)." ".$button."<br><span class='label label-danger'>Belum direview</span>";
                    }

                    return $nilai;                 
                })
                 ->addColumn('r2',function($ret){
                    if (!$ret->reviewer2_evaluasi_hasil) {
                        $button="<button type=\"button\" class=\"btn btn-primary btn-sm\" onclick=\"tambah_reviewer_monev('".$ret->id_usulan."','2')\"><i class=\"glyphicon glyphicon-plus\"></i> Input Reviewer</button>";
                        return $button;
                    }
                    $nilai="Belum";
                    if ($ret->reviewer2_evaluasi_hasil->status_review=='sudah') {
                        $nilai=$ret->reviewer2_evaluasi_hasil->nilai." <br><a alt='Download Hasil Review' href='".url('review/download-review/'.encrypt($ret->reviewer2_evaluasi_hasil->id_reviewer_usulan))."' class='btn btn-sm btn-primary'><i class='fa fa-file'></i> <a>";
                    }
                    elseif ($ret->reviewer2_evaluasi_hasil->status_review=='proses') {
                        $nilai="Sedang direview oleh ".Helpers::nama_gelar($ret->reviewer2_proposal->pegawai);
                    }elseif ($ret->reviewer2_evaluasi_hasil->status_review=='belum') {
                        $button="<br><label class=\"label label-primary\" onclick=\"tambah_reviewer_monev('".$ret->id_usulan."','2')\"><i class=\"glyphicon glyphicon-edit\"></i></label>";
                         $button.=" | <label class=\"label label-danger\" onclick=\"hapus_reviewer_monev('".$ret->reviewer2_evaluasi_hasil->id_reviewer_usulan."','".Helpers::nama_gelar($ret->reviewer2_evaluasi_hasil->pegawai)."','".$ret->judul."')\"><i class=\"glyphicon glyphicon-trash\"></i></label>";
                        $nilai=Helpers::nama_gelar($ret->reviewer2_evaluasi_hasil->pegawai)." ".$button."<br><span class='label label-danger'>Belum direview</span>";
                    }
                    return $nilai;                    
                })
                 ->addColumn('status_usulan',function($ret){
                    $des=Helpers::status_usulan($ret->status);
                    if ($ret->waktu_ajukan_revisi_proposal!=null) {
                      $des.="<br>Direvisi pada ".Tanggal::time_indo($ret->waktu_ajukan_revisi_proposal);
                    }

                    return $des;
                })
                ->escapeColumns('pelaksana')
                ->addIndexColumn()->make(true);
                return $dt;
        }
        
        return view('admin.kelola-usulan.monitoring-dan-evaluasi.monev-page',compact('taaktif'));
    }
    
    public function show($id)
    {
      $data=Usulan::find(decrypt($id));
      return view('admin.kelola-usulan.monitoring-dan-evaluasi.detail-usulan-page',compact('data'));
    }
    public function storeReviewerMonev(Request $request)
    {
        $cek=ReviewerUsulan::where('usulan_id',$request->usulan_id)
              ->where('reviewer_ke',$request->reviewer_ke)
              ->where('tahap','evaluasi-hasil')
              ->first();
        
        $data=[
            'usulan_id'=>$request->usulan_id,
            'tahap'=>'evaluasi-hasil',
            'reviewer_id'=>$request->reviewer_id,
            'reviewer_ke'=>$request->reviewer_ke,
            
          ];
        if (!$cek) {
          $insert=ReviewerUsulan::create($data);  
        }else{
          $insert=$cek->update($data);
        }

        
        $rev=ReviewerUsulan::where('usulan_id',$request->usulan_id)
              ->where('reviewer_ke',$request->reviewer_ke)
              ->first();
        Helpers::alert('success','Berhasil tambah '.Helpers::nama_gelar($rev->pegawai).' sebagai reviewer ke '.$rev->reviewer_ke.' usulan kegiatan '.$rev->usulan->buka_penerimaan->jenis_usulan->jenis_usulan.' dengan judul '.$rev->usulan->judul);
        Helpers::log('Menambah '.Helpers::nama_gelar($rev->pegawai).' sebagai reviewer ke '.$rev->reviewer_ke.' usulan kegiatan '.$rev->usulan->buka_penerimaan->jenis_usulan->jenis_usulan.' dengan judul '.$rev->usulan->judul);
        return back();

    }
    public function destroyReviewer($id_reviewer_usulan)
    {
       $cek=ReviewerUsulan::find($id_reviewer_usulan);
        DB::table('usulan_has_borang as a')->where('a.reviewer_id',$cek->reviewer_id)
        ->where('a.usulan_id',$cek->usulan_id)
        ->join('borang as b','a.borang_id','=','b.id_borang')
        ->where('b.tahap','evaluasi-hasil')
        ->delete();
        Helpers::alert('success','Berhasil hapus '.Helpers::nama_gelar($cek->pegawai).' sebagai reviewer ke '.$cek->reviewer_ke.' usulan kegiatan '.$cek->usulan->buka_penerimaan->jenis_usulan->jenis_usulan.' dengan judul '.$cek->usulan->judul);
        Helpers::log('Menghapus hapus '.Helpers::nama_gelar($cek->pegawai).' sebagai reviewer ke '.$cek->reviewer_ke.' usulan kegiatan '.$cek->usulan->buka_penerimaan->jenis_usulan->jenis_usulan.' dengan judul '.$cek->usulan->judul);
        $cek->delete();
        return back();
    }
    
    public function excelReviewMonev(Request $request)
    {
        $data=$this->dataUsulan($request)->get();

         return Excel::create('Rekap Data Penilaian Monev', function($excel) use ($data){
                $excel->sheet('Rekap',function($sheet)use($data){
                  $sheet->setOrientation('landscape');
                  $sheet->setStyle(array(
                      'font' => array(
                          'name'      =>  'Calibri',
                          'size'      =>  10,
                      )
                  ));
                  $sheet->setWidth('A',4);//NOMOR
                  $sheet->setWidth('B',27); //nama ketua
                  $sheet->setWidth('C',13); // NIDN
                  $sheet->setWidth('D',27); // nama anggota
                  $sheet->setWidth('E',13); // NIDN anggota
                  $sheet->setWidth('F',20); // Unit Kerja
                  $sheet->setWidth('G',17); // Skim
                  $sheet->setWidth('H',17);// Jenis
                  $sheet->setWidth('I',43); // Judul
                  $sheet->setWidth('J',10); // R1
                  $sheet->setWidth('K',14); // Rekomendasi
                  $sheet->setWidth('L',10); //R2
                  $sheet->setWidth('M',14); //Rekomendasi
                  
                  
                  

                  //format kolom
                  $sheet->setColumnFormat([
                    'C' =>  '0000000000',
                    'E' =>  '0000000000',


                ]);

                  //heading tabel
                  $sheet->mergeCells('A1:A2');
                  $sheet->setCellValue('A1', 'No');
                  
                  $sheet->mergeCells('B1:B2');
                  $sheet->setCellValue('B1', 'Nama Ketua');
                  
                  $sheet->mergeCells('C1:C2');
                  $sheet->setCellValue('C1', 'NIDK/NIDN');

                  $sheet->mergeCells('D1:E1');
                  $sheet->setCellValue('D1', 'Anggota');
                  $sheet->setCellValue('D2', 'Nama');

                  $sheet->setCellValue('E2', 'NIDK/NIDN');

                  $sheet->mergeCells('F1:F2');
                  $sheet->setCellValue('F1', 'Unit Kerja');
                   $sheet->mergeCells('G1:G2');
                  $sheet->setCellValue('G1', 'Skim');

                  $sheet->mergeCells('H1:H2');
                  $sheet->setCellValue('H1', 'Jenis');

                  $sheet->mergeCells('I1:I2');
                  $sheet->setCellValue('I1', 'Judul');

                  $sheet->mergeCells('J1:M1');
                  $sheet->setCellValue('J1', 'Penilaian Monev');
                  $sheet->setCellValue('J2', 'R1');
                  $sheet->setCellValue('K2', 'Rekomendasi');
                  $sheet->setCellValue('L2', 'R2');
                  $sheet->setCellValue('M2', 'Rekomendasi');


                 

                 
                  $sheet->getStyle('A1:Z2')->getAlignment()->setWrapText(true);
                  $sheet->cells("A1:Z2", function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                  });

                  

                   //data
                  $startrow=3;
                  $startrow2=3;

                  foreach ($data as $index => $ob) {
                      $no=$index+1;
                      if ($ob->jumlah_anggota==0) $mergeto=$startrow;
                      else $mergeto=$ob->jumlah_anggota +$startrow-1;
                      $sheet->mergeCells('A'.$startrow.':A'.$mergeto);
                      $sheet->setCellValue('A'.$startrow,$no);

                      $sheet->mergeCells('B'.$startrow.':B'.$mergeto);
                      $sheet->setCellValue('B'.$startrow,Helpers::nama_gelar($ob->ketua->pegawai));
                      
                      $sheet->mergeCells('C'.$startrow.':C'.$mergeto);
                      $sheet->setCellValue('C'.$startrow," ".$ob->ketua->pegawai->nip);

                      foreach ($ob->anggota as $no => $anggota) {

                          $sheet->setCellValue('D'.$startrow2,Helpers::nama_gelar($anggota->pegawai));
                          $sheet->setCellValue('E'.$startrow2," ".$anggota->pegawai->nip);
                          $startrow2++;
                          
                      }
                      $sheet->mergeCells('F'.$startrow.':F'.$mergeto);
                      $sheet->setCellValue('F'.$startrow,$ob->buka_penerimaan->unit_kerja->nama_unit);
                      $sheet->mergeCells('G'.$startrow.':G'.$mergeto);
                      $sheet->setCellValue('G'.$startrow,$ob->buka_penerimaan->skim->nama_skim); 
                      $sheet->mergeCells('H'.$startrow.':H'.$mergeto);
                      $sheet->setCellValue('H'.$startrow,$ob->jenis_skema->nama_jenis_skema);
                      $sheet->mergeCells('I'.$startrow.':I'.$mergeto);
                      $sheet->setCellValue('I'.$startrow,$ob->judul);                  
                      

                        if (!$ob->reviewer1_evaluasi_hasil) {
                        $nilai= 'Belum Input Reviewer';
                        $rekomendasi='-';
                        }else{
                            $nilai="Belum";
                            $rekomendasi="-";
                            if ($ob->reviewer1_evaluasi_hasil->status_review=='sudah') {
                                $nilai=$ob->reviewer1_evaluasi_hasil->nilai;
                                $rekomendasi=$ob->reviewer1_evaluasi_hasil->rekomendasi;
                            }elseif ($ob->reviewer1_evaluasi_hasil->status_review=='proses') {
                                $nilai="Proses";
                            }
                        }
                        

                      $sheet->mergeCells('J'.$startrow.':J'.$mergeto);
                      $sheet->setCellValue('J'.$startrow,$nilai);
                      $sheet->mergeCells('K'.$startrow.':K'.$mergeto);
                      $sheet->setCellValue('K'.$startrow,$rekomendasi);

                      if (!$ob->reviewer2_evaluasi_hasil) {
                        $nilai= 'Belum Input Reviewer';
                        $rekomendasi='-';
                        }else{
                            $nilai="Belum";
                            $rekomendasi="-";
                            if ($ob->reviewer2_evaluasi_hasil->status_review=='sudah') {
                                $nilai=$ob->reviewer2_evaluasi_hasil->nilai;
                                $rekomendasi=$ob->reviewer2_evaluasi_hasil->rekomendasi;
                            }elseif ($ob->reviewer2_evaluasi_hasil->status_review=='proses') {
                                $nilai="Proses";
                            }
                        }
                        

                      $sheet->mergeCells('L'.$startrow.':L'.$mergeto);
                      $sheet->setCellValue('L'.$startrow,$nilai);
                      $sheet->mergeCells('M'.$startrow.':M'.$mergeto);
                      $sheet->setCellValue('M'.$startrow,$rekomendasi);
                      
          


                      

                      $startrow=$startrow+$ob->jumlah_anggota;
                      $end=$startrow-1;    
                      $sheet->setBorder('A1:M'.$end, 'thin');
                      $sheet->getStyle('A3:Z'.$startrow)->getAlignment()->setWrapText(true);
                      
                    $sheet->cells("A3:A".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('center');
                      });
                    $sheet->cells("B3:B".$startrow, function($cells) {
                            $cells->setValignment('center');
                            
                    });
                      $sheet->cells("C3:C".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('center');
                      });
                      $sheet->cells("D3:D".$startrow, function($cells) {
                            $cells->setValignment('top');
                            
                      });
                      $sheet->cells("E3:E".$startrow, function($cells) {
                            $cells->setValignment('top');
                            $cells->setAlignment('center');
                      });
                      $sheet->cells("F3:I".$startrow, function($cells) {
                            $cells->setValignment('center');
                      });
                      $sheet->cells("J3:M".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('center');
                      });
                      $sheet->cells("N3:O".$startrow, function($cells) {
                            $cells->setValignment('center');
                      });

                  }
                 
                });

                 
               
               
              })->download('xlsx');
    }

}
