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
use Uang;
class SeleksiUsulanController extends Controller
{
    function __construct()
    {  
      $this->middleware('permission:read-seleksi-usulan')->only('index','show','excelReviewProposal','excelV1','excelV2');
      $this->middleware('permission:validasi-seleksi-usulan')->only('submitProsesUsulan');
      $this->middleware('permission:input-reviewer-proposal')->only('index','show','showusulan');
      $this->middleware('permission:delete-reviewer-proposal')->only('destroyReviewer');
      
    }
    private function dataUsulan($request)
    {

        $data=Usulan::
                when($request->tahun_anggaran_id,function($q) use($request){
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
                    $q->where('jenis_skema_id',$request->jenis_skema_id); 
                })
                ->when(!$request->status_usulan,function($q){
                 $q->whereIn('usulan.status',['sedang_diajukan','diterima','revisi','ditolak','revisi2']);
                })
                ->when($request->status_usulan,function($q) use($request){
                  if($request->status_usulan=='ajukan_revisi'){
                     
                      $q->where('usulan.status','diajukan')->whereNotNull('waktu_ajukan_revisi_proposal');
                  }
                  else{
                     
                      $q->where('usulan.status',$request->status_usulan);
                  }
                    
                })->when($request->status_review,function($q) use($request){
                  if($request->status_review=='belum_input'){
                     $q->where(function($r){

                        $r->doesntHave('reviewer1_proposal')->doesntHave('reviewer2_proposal','or');
                     });
                  }
                  else if($request->status_review=='belum_review'){
                     $q->whereHas('reviewer1_proposal',function($r){
                      $r->where('status_review','belum');
                     })->orWhereHas('reviewer2_proposal',function($r){
                      $r->where('status_review','belum');
                     });
                  }
                  else if($request->status_review=='sudah_review'){
                     $q->whereHas('reviewer1_proposal',function($r){
                      $r->where('status_review','sudah');
                     })->whereHas('reviewer2_proposal',function($r){
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
            
            $data=$this->dataUsulan($request)->select('usulan.*')->orderBy('usulan.total_nilai_reviewer_proposal','desc');
            $dt=DataTables::of($data)
                ->addColumn('ceklis',function($q){
                  if ($q->status_usulan!='belum') {
                    
                    return "<input type='checkbox' class='pilih' name='id_usulan[]' value='".$q->id_usulan."' >";
                  }else{
                    return '-';
                  }
              
                })
                ->addColumn('judul_link',function($ret){
                    return "<a href='".url('seleksi-usulan/'.encrypt($ret->id_usulan))."' target=\"_blank\"><u>".$ret->judul."</u></a>";
                })
                ->addColumn('pelaksana',function($ret){
                    $html="<table>";
                        foreach ($ret->pelaksana as $key => $value) {
                            $no=$key+1;
                          	if(!$value->pegawai){
                              $html.="Pegawai dengan id ".$value->id_peg."tidak ditemukan";
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
                    if (!$ret->reviewer1_proposal) {
                        $button="<button type=\"button\" type class=\"btn btn-primary btn-sm\" onclick=\"tambah_reviewer_proposal('".$ret->id_usulan."','1')\"><i class=\"glyphicon glyphicon-plus\"></i> Input Reviewer</button>";
                        return $button;
                    }
                    
                    
                    if ($ret->reviewer1_proposal->status_review=='sudah') {
                        $nilai=$ret->reviewer1_proposal->nilai."<br> <a alt='Download Hasil Review' href='".url('review/download-review/'.encrypt($ret->reviewer1_proposal->id_reviewer_usulan))."' class='btn btn-sm btn-primary'><i class='fa fa-file'></i> <a>";
                    }elseif ($ret->reviewer1_proposal->status_review=='proses') {
                        $nilai=Helpers::nama_gelar($ret->reviewer1_proposal->pegawai)."<br><span class='label label-primary'>Sedang direview</span>";
                    }elseif ($ret->reviewer1_proposal->status_review=='belum') {
                        $button="<br><label class=\"label label-primary\" onclick=\"tambah_reviewer_proposal('".$ret->id_usulan."','1')\"><i class=\"glyphicon glyphicon-edit\"></i></label>";
                        $button.=" | <label class=\"label label-danger\" onclick=\"hapus_reviewer_proposal('".$ret->reviewer1_proposal->id_reviewer_usulan."','".Helpers::nama_gelar($ret->reviewer1_proposal->pegawai)."','".$ret->judul."')\"><i class=\"glyphicon glyphicon-trash\"></i></label>";
                        $nilai=Helpers::nama_gelar($ret->reviewer1_proposal->pegawai)." ".$button."<br><span class='label label-danger'>Belum direview</span>";
                    }

                    return $nilai;                 
                })
                 ->addColumn('r2',function($ret){
                    if (!$ret->reviewer2_proposal) {
                        $button="<button type=\"button\" class=\"btn btn-primary btn-sm\" onclick=\"tambah_reviewer_proposal('".$ret->id_usulan."','2')\"><i class=\"glyphicon glyphicon-plus\"></i> Input Reviewer</button>";
                        return $button;
                    }
                    $nilai="Belum";
                    if ($ret->reviewer2_proposal->status_review=='sudah') {
                        $nilai=$ret->reviewer2_proposal->nilai." <br><a alt='Download Hasil Review' href='".url('review/download-review/'.encrypt($ret->reviewer2_proposal->id_reviewer_usulan))."' class='btn btn-sm btn-primary'><i class='fa fa-file'></i> <a>";
                    }
                    elseif ($ret->reviewer2_proposal->status_review=='proses') {
                         $nilai=Helpers::nama_gelar($ret->reviewer2_proposal->pegawai)."<br><span class='label label-primary'>Sedang direview</span>";
                    }elseif ($ret->reviewer2_proposal->status_review=='belum') {
                        $button="<br><label class=\"label label-primary\" onclick=\"tambah_reviewer_proposal('".$ret->id_usulan."','2')\"><i class=\"glyphicon glyphicon-edit\"></i></label>";
                         $button.=" | <label class=\"label label-danger\" onclick=\"hapus_reviewer_proposal('".$ret->reviewer2_proposal->id_reviewer_usulan."','".Helpers::nama_gelar($ret->reviewer2_proposal->pegawai)."','".$ret->judul."')\"><i class=\"glyphicon glyphicon-trash\"></i></label>";
                        $nilai=Helpers::nama_gelar($ret->reviewer2_proposal->pegawai)." ".$button."<br><span class='label label-danger'>Belum direview</span>";
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
        
        return view('admin.kelola-usulan.seleksi-usulan.seleksi-usulan-page',compact('taaktif'));
    }
    
    public function show($id)
    {
      $data=Usulan::find(decrypt($id));
      return view('admin.kelola-usulan.seleksi-usulan.detail-usulan-page',compact('data'));
    }
    public function storeReviewerProposal(Request $request)
    {
        $cek=ReviewerUsulan::where('usulan_id',$request->usulan_id)
              ->where('reviewer_ke',$request->reviewer_ke)
              ->where('tahap','proposal')
              ->first();
        
        $data=[
            'usulan_id'=>$request->usulan_id,
            'tahap'=>'proposal',
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
        ->where('b.tahap','proposal')
        ->delete();
        Helpers::alert('success','Berhasil hapus '.Helpers::nama_gelar($cek->pegawai).' sebagai reviewer ke '.$cek->reviewer_ke.' usulan kegiatan '.$cek->usulan->buka_penerimaan->jenis_usulan->jenis_usulan.' dengan judul '.$cek->usulan->judul);
        Helpers::alert('Menghapus '.Helpers::nama_gelar($cek->pegawai).' sebagai reviewer ke '.$cek->reviewer_ke.' usulan kegiatan '.$cek->usulan->buka_penerimaan->jenis_usulan->jenis_usulan.' dengan judul '.$cek->usulan->judul);
        $cek->delete();
        return back();
    }
    

    public function submitProsesUsulan(Request $request)
    {
        
        $input['status']=$request->proses_usulan;
        
        if($request->proses_usulan!='sedang_diajukan'){
          $input['validator_id']=auth()->user()->id_peg;
          $input['catatan_usulan']=$request->catatan_usulan;
          $input['tgl_validasi']=date('Y-m-d H:i:s');
        } else{
          $input['validator_id']=null;
          $input['catatan_usulan']=null;
          $input['tgl_validasi']=null;
        }
        $id_usulan=$request->id_usulan;
        foreach ($id_usulan as $key => $v) {
          $usulan=Usulan::find($v)->update($input);
          if ($input['status']=='diterima') {
            $usulan2=Usulan::find($v);
            //update review
            $jumlah_judul=$usulan2->buka_penerimaan->jumlah_judul;

            $jumlah_diterima=$usulan2->buka_penerimaan()->whereHas('usulan',function($q){
              $q->where('status','diterima');
            })->count();

            if ($jumlah_judul==$jumlah_diterima) {
              Usulan::where('buka_penerimaan_id',$usulan2->buka_penerimaan_id)->whereIn('status',['baru','revisi','sedang_diajukan'])->update([
                'status'=>'ditolak',
                'validator_id'=>auth()->user()->id_peg,
                'catatan_usulan'=>$request->catatan_usulan,
                'tgl_validasi'=>date('Y-m-d H:i:s'),

                ]);
            }
              
          }
         



        }
        Helpers::log('mensubmit proses seleksi usulan menjadi '.$request->proses_usulan.' pada usulan ber ID '.json_encode($request->id_usulan));

        return 'sukses';
         
        
        

        
    }

   
    
    public function excelReviewProposal(Request $request)
    {
        $data=$this->dataUsulan($request)->get();
        if ($request->has('rekap_excel')) {
          if ($request->rekap_excel=='v1') {
            return $this->excelV1($data);
          }
          if ($request->rekap_excel=='v2') {
            return $this->excelV2($data);
          }

        }
         return $this->excelReview($data);
    }
    private function excelReview($data)
    {
      return Excel::create('Rekap Data Penilaian', function($excel) use ($data){
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
                  $sheet->setWidth('N',45); //catatan revis
                  $sheet->setWidth('O',15); //waktu revisi
                  
                  

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
                  $sheet->setCellValue('J1', 'Penilaian Proposal');
                  $sheet->setCellValue('J2', 'R1');
                  $sheet->setCellValue('K2', 'Rekomendasi');
                  $sheet->setCellValue('L2', 'R2');
                  $sheet->setCellValue('M2', 'Rekomendasi');


                  $sheet->mergeCells('N1:N2');
                  $sheet->setCellValue('N1', 'Catatan Revisi Proposal Oleh Pengusul');
                  $sheet->mergeCells('O1:O2');
                  $sheet->setCellValue('O1', 'Waktu Revisi');

                 
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
                      

                        if (!$ob->reviewer1_proposal) {
                        $nilai= 'Belum Input Reviewer';
                        $rekomendasi='-';
                        }else{
                            $nilai="Belum";
                            $rekomendasi="-";
                            if ($ob->reviewer1_proposal->status_review=='sudah') {
                                $nilai=$ob->reviewer1_proposal->nilai;
                                $rekomendasi=$ob->reviewer1_proposal->rekomendasi;
                            }elseif ($ob->reviewer1_proposal->status_review=='proses') {
                                $nilai="Proses";
                            }
                        }
                        

                      $sheet->mergeCells('J'.$startrow.':J'.$mergeto);
                      $sheet->setCellValue('J'.$startrow,$nilai);
                      $sheet->mergeCells('K'.$startrow.':K'.$mergeto);
                      $sheet->setCellValue('K'.$startrow,$rekomendasi);

                      if (!$ob->reviewer2_proposal) {
                        $nilai= 'Belum Input Reviewer';
                        $rekomendasi='-';
                        }else{
                            $nilai="Belum";
                            $rekomendasi="-";
                            if ($ob->reviewer2_proposal->status_review=='sudah') {
                                $nilai=$ob->reviewer2_proposal->nilai;
                                $rekomendasi=$ob->reviewer2_proposal->rekomendasi;
                            }elseif ($ob->reviewer2_proposal->status_review=='proses') {
                                $nilai="Proses";
                            }
                        }
                        

                      $sheet->mergeCells('L'.$startrow.':L'.$mergeto);
                      $sheet->setCellValue('L'.$startrow,$nilai);
                      $sheet->mergeCells('M'.$startrow.':M'.$mergeto);
                      $sheet->setCellValue('M'.$startrow,$rekomendasi);
                      
                      $revisi_proposal='';
                      $tgl_revisi_proposal='-';
                      if ($ob->waktu_ajukan_revisi_proposal!=null) {
                        $revisi_proposal=$ob->catatan_revisi_proposal;
                        $tgl_revisi_proposal=Tanggal::time_indo($ob->waktu_ajukan_revisi_proposal);
                      }


                      $sheet->mergeCells('N'.$startrow.':N'.$mergeto);
                      $sheet->setCellValue('N'.$startrow,$revisi_proposal);
                      
                      $sheet->mergeCells('O'.$startrow.':O'.$mergeto);
                      $sheet->setCellValue('O'.$startrow,$tgl_revisi_proposal);

                      $startrow=$startrow+$ob->jumlah_anggota;    
                      $end=$startrow -1;
                      $sheet->setBorder('A1:O'.$end, 'thin');
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
    private  function excelV1($data)
    {
       return Excel::create('Rekap Data V1', function($excel) use ($data){
                $excel->sheet('Rekap',function($sheet)use($data){
                  $sheet->setOrientation('landscape');
                  $sheet->setStyle(array(
                      'font' => array(
                          'name'      =>  'Calibri',
                          'size'      =>  10,
                      )
                  ));
                  $sheet->setWidth('A',4);//NOMOR
                  $sheet->setWidth('B',30); //nama ketua
                  $sheet->setWidth('C',11); // nidn huruf
                  $sheet->setWidth('D',11); // nidn angka
                  $sheet->setWidth('E',8); // status ketua
                  $sheet->setWidth('F',8); // no urut
                  $sheet->setWidth('G',20); // nip
                  
                  $sheet->setWidth('H',20); // unit kerja
                  $sheet->setWidth('I',20); //unit kerja
                  $sheet->setWidth('J',25); //Skim Skema kecil
                  $sheet->setWidth('K',25); //Skim Skema besar
                  $sheet->setWidth('L',40); //Lokasi penelitian
                  $sheet->setWidth('M',60); //Judul
                  $sheet->setWidth('N',16); //jumlah dana
                  $sheet->setWidth('O',35); //terbilang
                  $sheet->setWidth('P',15); //jumlah dana 30
                  $sheet->setWidth('Q',35); //terbilang dana 30
                  $sheet->setWidth('R',21); // NPWP
                  $sheet->setWidth('S',20); // NAMA DIREKENEING
                  $sheet->setWidth('T',20); // NAMA BANK
                  $sheet->setWidth('U',30); // NOMOR REKENING
                  $sheet->setWidth('V',15); //jumlah dana 70
                  $sheet->setWidth('W',35); //terbilang dana 70

                  //anggota
                  $sheet->setWidth('X',30); //NAMA ANGGOTA
                  $sheet->setWidth('Y',11); // nidn huruf
                  $sheet->setWidth('Z',11); // nidn angka
                  $sheet->setWidth('AA',8); // no urut
                  $sheet->setWidth('AB',10); // status ANGGOTA

                  $sheet->setWidth('AC',30); //NAMA ANGGOTA
                  $sheet->setWidth('AD',11); // nidn huruf
                  $sheet->setWidth('AE',11); // nidn angka
                  $sheet->setWidth('AF',8); // no urut
                  $sheet->setWidth('AG',10); // status ANGGOTA

                  $sheet->setWidth('AH',30); //NAMA ANGGOTA
                  $sheet->setWidth('AI',11); // nidn huruf
                  $sheet->setWidth('AJ',11); // nidn angka
                  $sheet->setWidth('AK',8); // no urut
                  $sheet->setWidth('AL',10); // status ANGGOTA

                  $sheet->setWidth('AM',30); //NAMA ANGGOTA
                  $sheet->setWidth('AN',11); // nidn huruf
                  $sheet->setWidth('AO',11); // nidn angka
                  $sheet->setWidth('AP',8); // no urut
                  $sheet->setWidth('AQ',10); // status ANGGOTA
                  
                  $sheet->setWidth('AR',30); //NAMA ANGGOTA
                  $sheet->setWidth('AS',11); // nidn huruf
                  $sheet->setWidth('AT',11); // nidn angka
                  $sheet->setWidth('AU',8); // no urut
                  $sheet->setWidth('AV',10); // status ANGGOTA
                  $sheet->setWidth('AW',60); // luaran wajib
                  $sheet->setWidth('AX',40); // luaran tambahan

                  //pendukung
                  $sheet->setWidth('AY',20); // nomor rekening
                  $sheet->setWidth('AZ',25); // Nama direkening
                  $sheet->setWidth('BA',25); // Nomor NPWP

                  //format kolom
                 

                  //heading tabel
                  
                  $sheet->setCellValue('A1', 'No');                 
                  $sheet->setCellValue('B1', 'Nama Ketua');
                  $sheet->setCellValue('C1', 'NIDN Huruf');
                  $sheet->setCellValue('D1', 'NIDN Angka');
                  $sheet->setCellValue('E1', 'Status Ketua');
                  $sheet->setCellValue('F1', 'No.urut1');
                  $sheet->setCellValue('G1', 'NIP');
                  
                  $sheet->setCellValue('H1', 'Unit Kerja');
                  $sheet->setCellValue('I1', 'Unit Kerja Kecil');
                  $sheet->setCellValue('J1', 'Skim');
                  $sheet->setCellValue('K1', 'Skim Besar');
                  $sheet->setCellValue('L1', 'Lokasi Kegiatan');
                  $sheet->setCellValue('M1', 'Judul');
                  $sheet->setCellValue('N1', 'Jumlah Dana');
                  $sheet->setCellValue('O1', 'Terbilang');
                  $sheet->setCellValue('P1', 'Dana 30%');
                  $sheet->setCellValue('Q1', 'Terbilang');
                  $sheet->setCellValue('R1', 'NPWP');
                  $sheet->setCellValue('S1', 'Nama Rekening');
                  $sheet->setCellValue('T1', 'Nama Bank');
                  $sheet->setCellValue('U1', 'Nomor Rekening');
                  $sheet->setCellValue('V1', 'Dana 70%');
                  $sheet->setCellValue('W1', 'Terbilang 70%');

                  $sheet->setCellValue('X1', 'Anggota 1');
                  $sheet->setCellValue('Y1', 'NIDN Huruf 1');
                  $sheet->setCellValue('Z1', 'NIDN Anggota 1');
                  $sheet->setCellValue('AA1', 'Nomor Urut 1');
                  $sheet->setCellValue('AB1', 'Status Anggota 1');

                  $sheet->setCellValue('AC1', 'Anggota 2');
                  $sheet->setCellValue('AD1', 'NIDN Huruf 2');
                  $sheet->setCellValue('AE1', 'NIDN Anggota 2');
                  $sheet->setCellValue('AF1', 'Nomor Urut 2');
                  $sheet->setCellValue('AG1', 'Status Anggota 2');

                  $sheet->setCellValue('AH1', 'Anggota 3');
                  $sheet->setCellValue('AI1', 'NIDN Huruf 3');
                  $sheet->setCellValue('AJ1', 'NIDN Anggota 3');
                  $sheet->setCellValue('AK1', 'Nomor Urut 3');
                  $sheet->setCellValue('AL1', 'Status Anggota 3');

                  $sheet->setCellValue('AM1', 'Anggota 4');
                  $sheet->setCellValue('AN1', 'NIDN Huruf 4');
                  $sheet->setCellValue('AO1', 'NIDN Anggota 4');
                  $sheet->setCellValue('AP1', 'Nomor Urut 4');
                  $sheet->setCellValue('AQ1', 'Status Anggota 4');

                  $sheet->setCellValue('AR1', 'Anggota 5');
                  $sheet->setCellValue('AS1', 'NIDN Huruf 5');
                  $sheet->setCellValue('AT1', 'NIDN Anggota 5');
                  $sheet->setCellValue('AU1', 'Nomor Urut 5');
                  $sheet->setCellValue('AV1', 'Status Anggota 5');
                  $sheet->setCellValue('AW1', 'Luaran Wajib');
                  $sheet->setCellValue('AX1', 'Luaran Tambahan');
                  $sheet->setCellValue('AY1', 'Nomor Rekening');
                  $sheet->setCellValue('AZ1', 'Nama Direkening');
                  $sheet->setCellValue('BA1', 'Bank');
                  $sheet->setCellValue('BB1', 'Nomor NPWP');

                  $sheet->getStyle('A1:BB1')->getAlignment()->setWrapText(true);
                  $sheet->cells("A1:BB1", function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                  });


                   //data
                  $startrow=2;
                
                  foreach ($data as $index => $ob) {
                      $no=$index+1;
                      
                      $sheet->setCellValue('A'.$startrow,$no);
                      $sheet->setCellValue('B'.$startrow,Helpers::nama_gelar($ob->ketua->pegawai));
                      $sheet->setCellValue('C'.$startrow,'NIDN');
                      $sheet->setCellValue('D'.$startrow,$ob->ketua->pegawai->nip." ");
                      $sheet->setCellValue('E'.$startrow,'Ketua');
                      $sheet->setCellValue('F'.$startrow,1);
                      $sheet->setCellValue('G'.$startrow,$ob->ketua->pegawai->nip." ");
                      $sheet->setCellValue('H'.$startrow,strtoupper($ob->buka_penerimaan->unit_kerja->nama_unit));
                      $sheet->setCellValue('I'.$startrow,ucwords(strtolower($ob->buka_penerimaan->unit_kerja->nama_unit)));
                      $sheet->setCellValue('J'.$startrow,$ob->buka_penerimaan->skim->nama_skim);
                      $sheet->setCellValue('K'.$startrow,strtoupper($ob->buka_penerimaan->skim->nama_skim));
                      $sheet->setCellValue('L'.$startrow,Helpers::lokasi($ob));
                      $sheet->setCellValue('M'.$startrow,htmlentities($ob->judul));
                      $sheet->setCellValue('N'.$startrow,Uang::format_uang($ob->dana_perjudul));
                      $sheet->setCellValue('O'.$startrow,Uang::terbilang_bersih($ob->dana_perjudul));
                      $sheet->setCellValue('P'.$startrow,Uang::format_uang($ob->dana_perjudul*30/100 ));
                      $sheet->setCellValue('Q'.$startrow,Uang::terbilang_bersih($ob->dana_perjudul*30/100 ));
                      $nomor_npwp="Belum isi";
                      $nama_direkening="Belum isi";
                      $bank="Belum isi";
                      $nomor_rekening="Belum isi";

                      if ($ob->ketua->pegawai->data_pendukung) {
                       // dd($ob->ketua->pegawai->data_pendukung);
                        $nomor_npwp="".$ob->ketua->pegawai->data_pendukung->no_npwp." ";
                        $nama_direkening=strtoupper($ob->ketua->pegawai->data_pendukung->nama_direkening);
                        $bank=$ob->ketua->pegawai->data_pendukung->bank->nama_bank;
                        $nomor_rekening=$ob->ketua->pegawai->data_pendukung->no_rek." ";
                      }
                      $sheet->setCellValue('R'.$startrow,$nomor_npwp);
                      $sheet->setCellValue('S'.$startrow,$nama_direkening);
                      $sheet->setCellValue('T'.$startrow,$bank);
                      $sheet->setCellValue('U'.$startrow,$nomor_rekening);
                      
                      $sheet->setCellValue('V'.$startrow,Uang::format_uang($ob->dana_perjudul*70/100 ));
                      $sheet->setCellValue('W'.$startrow,Uang::terbilang_bersih($ob->dana_perjudul*70/100 ));

                      $anggota=$ob->anggota;
                      if ($ob->jumlah_anggota==1 && count($anggota) > 0 ) {
                          $sheet->setCellValue('X'.$startrow,Helpers::nama_gelar($anggota[0]));
                          $sheet->setCellValue('Y'.$startrow,'NIDN');
                          $sheet->setCellValue('Z'.$startrow,$anggota[0]->pegawai->nip." ");
                          $sheet->setCellValue('AA'.$startrow,2);
                          $sheet->setCellValue('AB'.$startrow,"ANGGOTA" );
                      }elseif ($ob->jumlah_anggota==2 && count($anggota) > 0) {
                          $sheet->setCellValue('X'.$startrow,Helpers::nama_gelar($anggota[0]));
                          $sheet->setCellValue('Y'.$startrow,'NIDN');
                          $sheet->setCellValue('Z'.$startrow,$anggota[0]->pegawai->nip." ");
                          $sheet->setCellValue('AA'.$startrow,2);
                          $sheet->setCellValue('AB'.$startrow,"ANGGOTA" );

                          $sheet->setCellValue('AC'.$startrow,Helpers::nama_gelar($anggota[1]));
                          $sheet->setCellValue('AD'.$startrow,'NIDN');
                          $sheet->setCellValue('AE'.$startrow,$anggota[1]->pegawai->nip." ");
                          $sheet->setCellValue('AF'.$startrow,3);
                          $sheet->setCellValue('AG'.$startrow,"ANGGOTA" );
                      }elseif ($ob->jumlah_anggota==3 && count($anggota) > 0) {
                          $sheet->setCellValue('X'.$startrow,Helpers::nama_gelar($anggota[0]));
                          $sheet->setCellValue('Y'.$startrow,'NIDN');
                          $sheet->setCellValue('Z'.$startrow,$anggota[0]->pegawai->nip." ");
                          $sheet->setCellValue('AA'.$startrow,2);
                          $sheet->setCellValue('AB'.$startrow,"ANGGOTA" );

                          $sheet->setCellValue('AC'.$startrow,Helpers::nama_gelar($anggota[1]));
                          $sheet->setCellValue('AD'.$startrow,'NIDN');
                          $sheet->setCellValue('AE'.$startrow,$anggota[1]->pegawai->nip." ");
                          $sheet->setCellValue('AF'.$startrow,3);
                          $sheet->setCellValue('AG'.$startrow,"ANGGOTA" );

                          $sheet->setCellValue('AH'.$startrow,Helpers::nama_gelar($anggota[2]));
                          $sheet->setCellValue('AI'.$startrow,'NIDN');
                          $sheet->setCellValue('AJ'.$startrow,$anggota[2]->pegawai->nip." ");
                          $sheet->setCellValue('AK'.$startrow,4);
                          $sheet->setCellValue('AL'.$startrow,"ANGGOTA" );
                      }elseif ($ob->jumlah_anggota==4 && count($anggota) > 0) {
                          $sheet->setCellValue('X'.$startrow,Helpers::nama_gelar($anggota[0]));
                          $sheet->setCellValue('Y'.$startrow,'NIDN');
                          $sheet->setCellValue('Z'.$startrow,$anggota[0]->pegawai->nip." ");
                          $sheet->setCellValue('AA'.$startrow,2);
                          $sheet->setCellValue('AB'.$startrow,"ANGGOTA" );

                          $sheet->setCellValue('AC'.$startrow,Helpers::nama_gelar($anggota[1]));
                          $sheet->setCellValue('AD'.$startrow,'NIDN');
                          $sheet->setCellValue('AE'.$startrow,$anggota[1]->pegawai->nip." ");
                          $sheet->setCellValue('AF'.$startrow,3);
                          $sheet->setCellValue('AG'.$startrow,"ANGGOTA" );

                          $sheet->setCellValue('AH'.$startrow,Helpers::nama_gelar($anggota[2]));
                          $sheet->setCellValue('AI'.$startrow,'NIDN');
                          $sheet->setCellValue('AJ'.$startrow,$anggota[2]->pegawai->nip." ");
                          $sheet->setCellValue('AK'.$startrow,4);
                          $sheet->setCellValue('AL'.$startrow,"ANGGOTA" );

                          $sheet->setCellValue('AM'.$startrow,Helpers::nama_gelar($anggota[3]));
                          $sheet->setCellValue('AN'.$startrow,'NIDN');
                          $sheet->setCellValue('AO'.$startrow,$anggota[3]->pegawai->nip." ");
                          $sheet->setCellValue('AP'.$startrow,5);
                          $sheet->setCellValue('AQ'.$startrow,"ANGGOTA" );
                      }
                      elseif ($ob->jumlah_anggota==5 && count($anggota) > 0) {
                          $sheet->setCellValue('X'.$startrow,Helpers::nama_gelar($anggota[0]));
                          $sheet->setCellValue('Y'.$startrow,'NIDN');
                          $sheet->setCellValue('Z'.$startrow,$anggota[0]->pegawai->nip." ");
                          $sheet->setCellValue('AA'.$startrow,2);
                          $sheet->setCellValue('AB'.$startrow,"ANGGOTA" );

                          $sheet->setCellValue('AC'.$startrow,Helpers::nama_gelar($anggota[1]));
                          $sheet->setCellValue('AD'.$startrow,'NIDN');
                          $sheet->setCellValue('AE'.$startrow,$anggota[1]->pegawai->nip." ");
                          $sheet->setCellValue('AF'.$startrow,3);
                          $sheet->setCellValue('AG'.$startrow,"ANGGOTA" );

                          $sheet->setCellValue('AH'.$startrow,Helpers::nama_gelar($anggota[2]));
                          $sheet->setCellValue('AI'.$startrow,'NIDN');
                          $sheet->setCellValue('AJ'.$startrow,$anggota[2]->pegawai->nip." ");
                          $sheet->setCellValue('AK'.$startrow,4);
                          $sheet->setCellValue('AL'.$startrow,"ANGGOTA" );

                          $sheet->setCellValue('AM'.$startrow,Helpers::nama_gelar($anggota[3]));
                          $sheet->setCellValue('AN'.$startrow,'NIDN');
                          $sheet->setCellValue('AO'.$startrow,$anggota[3]->pegawai->nip." ");
                          $sheet->setCellValue('AP'.$startrow,5);
                          $sheet->setCellValue('AQ'.$startrow,"ANGGOTA" );

                          $sheet->setCellValue('AR'.$startrow,Helpers::nama_gelar($anggota[4]));
                          $sheet->setCellValue('AS'.$startrow,'NIDN');
                          $sheet->setCellValue('AT'.$startrow,$anggota[4]->pegawai->nip );
                          $sheet->setCellValue('AU'.$startrow,6);
                          $sheet->setCellValue('AV'.$startrow,"ANGGOTA" );
                      }
                      $wajib="";
                      if ($ob->luaran_wajib->count() == 1) {
                        foreach ($ob->luaran_wajib as $key => $value) {
                          $wajib.=$value->nama_luaran;
                          if (count($ob->luaran_tambahan)==0) {
                              $wajib.=".";
                            }else{
                              $wajib.=" ";
                          }
                        }
                      }else{
                        foreach ($ob->luaran_wajib as $key => $value) {
                          $wajib.=$value->nama_luaran.', ';
                          
                          
                          if ($key+1 == $ob->luaran_wajib->count() ) {
                            
                            
                            $wajib.=$value->nama_luaran;
                            if (count($ob->luaran_tambahan)==0) {
                              $wajib.=".";
                            }else{
                              $wajib.=" ";
                            }
                            
                           
                          }
                          
                        }
                      }
                      $tambahan="";
                      if (count($ob->luaran_tambahan) >0  ) {
                        if ($ob->luaran_tambahan->count() == 1) {
                          foreach ($ob->luaran_tambahan as $key => $value) {
                            //$tambahan.="dan luaran tambahan berupa ".$value->nama_luaran.".";
                            $tambahan.=$value->nama_luaran.".";
                          }
                        }else{
                          //$tambahan.='dan luaran tambahan berupa ';
                          $tambahan.=' ';
                          foreach ($ob->luaran_tambahan as $key => $value) {
                            
                            if ($key+1 == $ob->luaran_tambahan->count()) {
                              $tambahan.=' dan '.$value->nama_luaran.'.';
                            }else{
                              $tambahan.=$value->nama_luaran;
                              if ($key+2 == $ob->luaran_tambahan->count()) {
                                $tambahan.=' ';
                              }else{
                                $tambahan.=', ';
                              }
                            }
                          }
                        }
                      }

                      $sheet->setCellValue('AW'.$startrow,$wajib);
                      $sheet->setCellValue('AX'.$startrow,$tambahan );

                      //
                      $sheet->setCellValue('AY'.$startrow,$ob->ketua->pegawai->data_pendukung->no_rek." ");
                      $sheet->setCellValue('AZ'.$startrow,$ob->ketua->pegawai->data_pendukung->nama_direkening);
                      $sheet->setCellValue('BA'.$startrow,$ob->ketua->pegawai->data_pendukung->bank->nama_bank);
                      $sheet->setCellValue('BB'.$startrow,$ob->ketua->pegawai->data_pendukung->no_npwp." ");
                     
                  
                      $end= $startrow - 1;    
                      $sheet->setBorder('A1:BB'.$startrow, 'thin');
                      
                      
                        
                      $sheet->cells("A2:A".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('center');
                      });

                      //$sheet->getStyle('B2:B'.$startrow)->getAlignment()->setWrapText(true);
                      $sheet->cells("B2:B".$startrow, function($cells) {
                              $cells->setValignment('center');
                      });

                      $sheet->cells("C2:G".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('center');
                      });

                       $sheet->cells("H2:X".$startrow, function($cells) {
                            $cells->setValignment('center');
                            
                       });
                       //$sheet->getStyle('K2:N'.$startrow)->getAlignment()->setWrapText(true);
                       

                       $sheet->cells("Y2:AB".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('center');
                      });

                        $sheet->cells("AC2:AC".$startrow, function($cells) {
                            $cells->setValignment('center');
                            
                       });
                      // $sheet->getStyle('R2:R'.$startrow)->getAlignment()->setWrapText(true);

                        $sheet->cells("AD2:AG".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('center');
                      });

                        $sheet->cells("AH2:AH".$startrow, function($cells) {
                            $cells->setValignment('center');
                            
                       });
                      // $sheet->getStyle('T2:T'.$startrow)->getAlignment()->setWrapText(true);

                       $sheet->cells("AI2:AL".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('center');
                      });
                       $sheet->cells("AM2:AM".$startrow, function($cells) {
                            $cells->setValignment('center');
                            
                       });
                        $sheet->cells("AN2:AQ".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('left');
                            
                      });

                       //$sheet->getStyle('AE2:AE'.$startrow)->getAlignment()->setWrapText(true);
                        $sheet->cells("AR2:AR".$startrow, function($cells) {
                            $cells->setValignment('center');
                            
                       });

                        $sheet->cells("AS2:AV".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('center');
                      });

                        $sheet->cells("AW2:BB".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('left');
                            
                      });
                      
                      
                  $startrow++;
                  
                  }
                  $sheet->getStyle('A2:BB'.$startrow)->getAlignment()->setWrapText(true);
                 
                });

                 
               
               
              })->download('xlsx');
    }
    private  function excelV2($data)
    {
       return Excel::create('Rekap Data Usulan V2', function($excel) use ($data){
                $excel->sheet('Rekap',function($sheet)use($data){
                  $sheet->setOrientation('landscape');
                  $sheet->setStyle(array(
                      'font' => array(
                          'name'      =>  'Calibri',
                          'size'      =>  10,
                      )
                  ));


                  $sheet->setWidth('A',4);//NOMOR
                  $sheet->setWidth('B',30); //nama ketua
                  $sheet->setWidth('C',11); // nidn huruf
                  $sheet->setWidth('D',11); // nidn angka
                  $sheet->setWidth('E',8); // status ketua
                  $sheet->setWidth('F',8); // no urut
                  $sheet->setWidth('G',20); // nip
                  $sheet->setWidth('H',20); // proposal
                  $sheet->setWidth('I',20); // laporan akhir
                  $sheet->setWidth('J',20); //unit
                  $sheet->setWidth('K',25); //Unit kerja kecil
                  $sheet->setWidth('L',25); //Skim Skema kcil
                  $sheet->setWidth('M',35); //skim besar
                  $sheet->setWidth('N',35); //lokasi
                  $sheet->setWidth('O',35); //judul
                  $sheet->setWidth('P',20); //jumlah dana
                  $sheet->setWidth('Q',35); //terbilang
                  $sheet->setWidth('R',20); //dana 30
                  $sheet->setWidth('S',35); // terbilang dana 30
                  $sheet->setWidth('T',25); // npwp
                  $sheet->setWidth('U',25); // namadirek 
                  $sheet->setWidth('V',17); // nama bank
                  $sheet->setWidth('W',15); //nomor rek
                  $sheet->setWidth('X',20); //dana 70
                  $sheet->setWidth('Y',35); //terbilang 70
                  $sheet->setWidth('Z',8); // No urut
                  $sheet->setWidth('AA',11); // nidn huruf
                  $sheet->setWidth('AB',11); // nidn angka
                  $sheet->setWidth('AC',30); // Nama
                  $sheet->setWidth('AD',12); // status ANGGOTA
                  
                  //heading tabel
                  $sheet->mergeCells('A1:A2');
                  $sheet->setCellValue('A1', 'No');
                  
                  $sheet->mergeCells('B1:B2');
                  $sheet->setCellValue('B1', 'Nama Ketua');
                  
                  $sheet->mergeCells('C1:C2');
                  $sheet->setCellValue('C1', 'NIDN Huruf');

                  $sheet->mergeCells('D1:D2');
                  $sheet->setCellValue('D1', 'NIDN Angka');

                  $sheet->mergeCells('E1:E2');
                  $sheet->setCellValue('E1', 'Status Ketua');

                  $sheet->mergeCells('F1:F2');
                  $sheet->setCellValue('F1', 'No.urut1');

                  $sheet->mergeCells('G1:G2');
                  $sheet->setCellValue('G1', 'NIP');

                
                  $sheet->mergeCells('H1:H2');
                  $sheet->setCellValue('H1', 'File Proposal');

                  $sheet->mergeCells('I1:I2');
                  $sheet->setCellValue('I1', 'File Laporan Akhir');

                  $sheet->mergeCells('J1:J2');
                  $sheet->setCellValue('J1', 'Unit Kerja');

                  $sheet->mergeCells('K1:K2');
                  $sheet->setCellValue('K1', 'Unit Kerja Kecil');

                  $sheet->mergeCells('L1:L2');
                  $sheet->setCellValue('L1', 'Skim');

                  $sheet->mergeCells('M1:M2');
                  $sheet->setCellValue('M1', 'Skim Besar');

                  $sheet->mergeCells('N1:N2');
                  $sheet->setCellValue('N1', 'Lokasi Kegiatan');

                  $sheet->mergeCells('O1:O2');
                  $sheet->setCellValue('O1', 'Judul');

                  $sheet->mergeCells('P1:P2');
                  $sheet->setCellValue('P1', 'Jumlah Dana');

                  $sheet->mergeCells('Q1:Q2');
                  $sheet->setCellValue('Q1', 'Terbilang');

                  $sheet->mergeCells('R1:R2');
                  $sheet->setCellValue('R1', 'Dana 30%');

                  $sheet->mergeCells('S1:S2');
                  $sheet->setCellValue('S1', 'Terbilang');

                  
                 
                   $sheet->mergeCells('T1:T2');
                  $sheet->setCellValue('T1', 'NPWP');

                   $sheet->mergeCells('U1:U2');
                  $sheet->setCellValue('U1', 'Nama Rekening');

                   $sheet->mergeCells('V1:V2');
                  $sheet->setCellValue('V1', 'Nama Bank');

                   $sheet->mergeCells('W1:W2');
                  $sheet->setCellValue('W1', 'Nomor Rekening');

                   $sheet->mergeCells('X1:X2');
                  $sheet->setCellValue('X1', 'Dana 70%');

                   $sheet->mergeCells('Y1:Y2');
                  $sheet->setCellValue('Y1', 'Terbilang 70%');

                  $sheet->mergeCells('Z1:AD1');

                  $sheet->cell("Z1", function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                  });

                  $sheet->setCellValue('Z1', 'Anggota');
                  $sheet->setCellValue('Z2', 'Nomor Urut');
                  $sheet->setCellValue('AA2', 'NIDN Huruf');
                  $sheet->setCellValue('AB2', 'NIDN Anggota');
                  $sheet->setCellValue('AC2', 'Nama Dosen');
                  $sheet->setCellValue('AD2', 'Status');


                  $sheet->getStyle('A1:AD2')->getAlignment()->setWrapText(true);
                  $sheet->cells("A1:AD2", function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                  });
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
                      $sheet->setCellValue('C'.$startrow,'NIDN');

                      $sheet->mergeCells('D'.$startrow.':D'.$mergeto);
                      $sheet->setCellValue('D'.$startrow,$ob->ketua->pegawai->nip." ");
                      
                      $sheet->mergeCells('E'.$startrow.':E'.$mergeto);
                      $sheet->setCellValue('E'.$startrow,'Ketua');

                      $sheet->mergeCells('F'.$startrow.':F'.$mergeto);
                      $sheet->setCellValue('F'.$startrow,1);

                      $sheet->mergeCells('G'.$startrow.':G'.$mergeto);
                      
                      $sheet->setCellValue('G'.$startrow,$ob->ketua->pegawai->nip." ");

                
                      $sheet->mergeCells('H'.$startrow.':H'.$mergeto);

                      
                      $sheet->getCell('H'.$startrow)
                            ->setValueExplicit("Download", \PHPExcel_Cell_DataType::TYPE_STRING)
                            ->getHyperlink()
                            ->setUrl($this->baseurl.'/dokumen/proposal/' . $ob->file_proposal)
                            ->setTooltip('Download');

                      $sheet->mergeCells('I'.$startrow.':I'.$mergeto);
                      
                      if ($ob->file_laporan_akhir!=null||$ob->file_laporan_akhir!="") {
                        $sheet->getCell('I'.$startrow)
                            ->setValueExplicit("Download", \PHPExcel_Cell_DataType::TYPE_STRING)
                            ->getHyperlink()
                            ->setUrl($this->baseurl.'/dokumen/laporan-akhir/' . $ob->file_laporan_akhir)
                            ->setTooltip('Download');
                      }else{
                        $sheet->setCellValue('I'.$startrow,'Belum Upload');
                      }
                      

                      $sheet->cells('H'.$startrow.':I'.$startrow,function($cells){
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                        $cells->setFontColor('#0000FF');
                      });

                

                      $sheet->mergeCells('J'.$startrow.':J'.$mergeto);
                      $sheet->setCellValue('J'.$startrow,strtoupper($ob->buka_penerimaan->unit_kerja->nama_unit));

                      $sheet->mergeCells('K'.$startrow.':K'.$mergeto);
                      $sheet->setCellValue('K'.$startrow,ucwords(strtolower($ob->buka_penerimaan->unit_kerja->nama_unit)));

                       $sheet->mergeCells('L'.$startrow.':L'.$mergeto);
                      $sheet->setCellValue('L'.$startrow,$ob->buka_penerimaan->skim->nama_skim);

                       $sheet->mergeCells('M'.$startrow.':M'.$mergeto);
                      $sheet->setCellValue('M'.$startrow,strtoupper($ob->buka_penerimaan->skim->nama_skim));

                      $sheet->mergeCells('N'.$startrow.':N'.$mergeto);
                      $sheet->setCellValue('N'.$startrow,Helpers::lokasi($ob));

                      $sheet->mergeCells('O'.$startrow.':O'.$mergeto);
                      $sheet->setCellValue('O'.$startrow,htmlentities($ob->judul));

                      $sheet->mergeCells('P'.$startrow.':P'.$mergeto);
                      $sheet->setCellValue('P'.$startrow,Uang::format_uang($ob->dana_perjudul));

                       $sheet->mergeCells('Q'.$startrow.':Q'.$mergeto);
                      $sheet->setCellValue('Q'.$startrow,Uang::terbilang_bersih($ob->dana_perjudul));

                      $sheet->mergeCells('R'.$startrow.':R'.$mergeto);
                      $sheet->setCellValue('R'.$startrow,Uang::format_uang($ob->dana_perjudul*30/100 ));

                      $sheet->mergeCells('S'.$startrow.':S'.$mergeto);
                      $sheet->setCellValue('S'.$startrow,Uang::terbilang_bersih($ob->dana_perjudul*30/100 ));

                      $nomor_npwp="Belum isi";
                      $nama_direkening="Belum isi";
                      $bank="Belum isi";
                      $nomor_rekening="Belum isi";

                      if ($ob->ketua->pegawai->data_pendukung) {
                       // dd($ob->ketua->pegawai->data_pendukung);
                        $nomor_npwp="".$ob->ketua->pegawai->data_pendukung->no_npwp." ";
                        $nama_direkening=strtoupper($ob->ketua->pegawai->data_pendukung->nama_direkening);
                        $bank=$ob->ketua->pegawai->data_pendukung->bank->nama_bank;
                        $nomor_rekening=$ob->ketua->pegawai->data_pendukung->no_rek." ";
                      }

                      $sheet->mergeCells('T'.$startrow.':T'.$mergeto);
                      $sheet->setCellValue('T'.$startrow,$nomor_npwp);

                      $sheet->mergeCells('U'.$startrow.':U'.$mergeto);
                      $sheet->setCellValue('U'.$startrow,strtoupper($nama_direkening));

                      $sheet->mergeCells('V'.$startrow.':V'.$mergeto);
                      $sheet->setCellValue('V'.$startrow,$bank);

                      $sheet->mergeCells('W'.$startrow.':W'.$mergeto);
                      $sheet->setCellValue('W'.$startrow,strtoupper($nomor_rekening));

                       $sheet->mergeCells('X'.$startrow.':X'.$mergeto);
                      $sheet->setCellValue('X'.$startrow,Uang::format_uang($ob->dana_perjudul*70/100 ));

                      $sheet->mergeCells('Y'.$startrow.':Y'.$mergeto);
                      $sheet->setCellValue('Y'.$startrow,Uang::terbilang_bersih($ob->dana_perjudul*70/100 ));







                      $no_urut=1;
                      foreach ($ob->anggota as $no => $anggota) {
                          $sheet->setCellValue('Z'.$startrow2,$no_urut.'. ');
                          $sheet->setCellValue('AA'.$startrow2,'NIDN');
                          $sheet->setCellValue('AB'.$startrow2,$anggota->pegawai->nip." ");
                          $sheet->setCellValue('AC'.$startrow2,Helpers::nama_gelar($anggota->pegawai));
                          $sheet->setCellValue('AD'.$startrow2,"ANGGOTA" );

                          $startrow2++;
                          $no_urut++;
                      }
                  
                      $startrow=$startrow+$ob->jumlah_anggota;    
                      
                      
                      
                        
                      $sheet->cells("A3:A".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('center');
                      });

                      
                      $sheet->cells("B3:B".$startrow, function($cells) {
                              $cells->setValignment('center');
                      });

                      $sheet->cells("C3:I".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('center');
                      });

                       $sheet->cells("J3:Y".$startrow, function($cells) {
                            $cells->setValignment('center');
                            
                       });
                       

                       $sheet->cells("Z3:AB".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('center');
                      });

                        $sheet->cells("AC3:AC".$startrow, function($cells) {
                            $cells->setValignment('center');
                            
                       });
                      $sheet->cells("AD3:AD".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('center');
                      });
                       
                      $end= $startrow - 1;
                     $sheet->getStyle('B3:AD'.$startrow)->getAlignment()->setWrapText(true);
                     $sheet->setBorder('A1:AD'.$end, 'thin');
                     
                      
                  }
                  

                  
                 
                });

                 
               
               
              })->download('xlsx');
    }

}
