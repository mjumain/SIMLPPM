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
class LaporanKegiatanController extends Controller
{
    function __construct()
    {  
      $this->middleware('permission:read-laporan-kegiatan')->only('index','show');
      $this->middleware('permission:memvalidasi-laporan')->only('ceklisHardCopy');
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
                ->when($request->luaran_id,function($q) use($request){  
                    $q->whereHas('luaran_wajib',function($r)use($request){
                      $r->where('id_luaran',$request->luaran_id);
                    })
                    ->orWhereHas('luaran_tambahan',function($r)use($request){
                      $r->where('id_luaran',$request->luaran_id);
                    });
                })
                ->when($request->id_peg,function($q) use($request){  
                    $q->whereHas('pelaksana',function($r)use($request){
                      $r->where('id_peg',$request->id_peg);
                    });
                    
                })
                ->when($request->laporan,function($q) use($request){
                  if($request->laporan=='belum_lengkap_upload'){
                     $q->where(function($r){

                        $r->whereNull('file_laporan_kemajuan')
                        ->orWhereNull('file_laporan_akhir')
                        ->orWhereNull('file_artikel')
                        ->orWhereNull('file_luaran');
                     });
                  }
                  else if($request->laporan=='belum_lengkap_hardcopy'){
                     $q->where(function($r){

                        $r->where('hard_proposal','!=',1)
                        ->orWhere('hard_laporan_kemajuan','!=',1)
                        ->orWhere('hard_laporan_akhir','!=',1)
                        ->orWhere('hard_artikel','!=',1)
                        ->orWhere('hard_luaran','!=',1);
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
                // ->addColumn('ceklis',function($q){
                //   return "<input type='checkbox' class='pilih' name='id_usulan[]' value='".$q->id_usulan."' >";
              
                // })
                ->addColumn('judul_link',function($ret){
                    return "<a href='".url('laporan-kegiatan/'.encrypt($ret->id_usulan))."' target=\"_blank\"><u>".$ret->judul."</u></a>";
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
                 ->addColumn('soft',function($ret){
                    $soft='<ul>';
                    $soft.="<li>Laporan Kemajuan </br>".Helpers::cek_dokumen($ret->file_laporan_kemajuan,'laporan-kemajuan');
                    $soft.="<li>Laporan Akhir </br>".Helpers::cek_dokumen($ret->file_laporan_akhir,'laporan-akhir');
                    $soft.="<li>Artikel </br> ".Helpers::cek_dokumen($ret->file_artikel,'artikel');
                    $soft.="<li>Luaran </br> ".Helpers::cek_dokumen($ret->file_luaran,'luaran');
                    $soft.='</ul>';

                    return $soft;                 
                })
                 ->addColumn('hard',function($ret){

                    ($ret->hard_proposal==1) ? $hard_proposal='checked':$hard_proposal='';
                    ($ret->hard_laporan_kemajuan==1) ? $hard_laporan_kemajuan='checked':$hard_laporan_kemajuan='';
                    ($ret->hard_laporan_akhir==1) ? $hard_laporan_akhir='checked':$hard_laporan_akhir='';
                    ($ret->hard_artikel==1) ? $hard_artikel='checked':$hard_artikel='';
                    ($ret->hard_luaran==1) ? $hard_luaran='checked':$hard_luaran='';

                    $hard="<table width='100%'>";
                    $hard.="<tr>
                              <td valign='top' width=15%><input type=\"checkbox\" ".$hard_proposal." id='hard_proposal".$ret->id_usulan."' onclick=\"ceklis_hardcopy('".$ret->id_usulan."','hard_proposal') \"  ></td>
                              <td>Proposal </td>
                            </tr>";
                    $hard.="<tr>
                              <td valign='top'><input type=\"checkbox\" ".$hard_laporan_kemajuan." id='hard_laporan_kemajuan".$ret->id_usulan."' onclick=\"ceklis_hardcopy('".$ret->id_usulan."','hard_laporan_kemajuan') \"  ></td>
                              <td>Laporan Kemajuan </td>
                            </tr>";
                    $hard.="<tr>
                              <td valign='top'><input type=\"checkbox\" ".$hard_laporan_akhir." id='hard_laporan_akhir".$ret->id_usulan."' onclick=\"ceklis_hardcopy('".$ret->id_usulan."','hard_laporan_akhir') \"  ></td>
                              <td>Laporan Akhir </td>
                            </tr>";
                    $hard.="<tr>
                              <td valign='top'><input type=\"checkbox\" ".$hard_artikel." id='hard_artikel".$ret->id_usulan."' onclick=\"ceklis_hardcopy('".$ret->id_usulan."','hard_artikel') \"  ></td>
                              <td>Artikel </td>
                            </tr>";
                    $hard.="<tr>
                              <td valign='top'><input type=\"checkbox\" ".$hard_luaran." id='hard_luaran".$ret->id_usulan."' onclick=\"ceklis_hardcopy('".$ret->id_usulan."','hard_luaran') \"  ></td>
                              <td>Luaran </td>
                            </tr>";


                    $hard.="</table>";

                    return $hard;                    
                })
                
                ->escapeColumns('pelaksana')
                ->addIndexColumn()->make(true);
                return $dt;
        }
        
        return view('admin.kelola-usulan.laporan-kegiatan.laporan-kegiatan-page',compact('taaktif'));
    }
    
    public function show($id)
    {
      $data=Usulan::find(decrypt($id));
      return view('admin.kelola-usulan.laporan-kegiatan.detail-usulan-page',compact('data'));
    }

    public function ceklisHardCopy(Request $r)
    {

      $update=Usulan::find($r->id_usulan);
      $r->status==0 ? $status='belum menyerahkan':$status='sudah menyerahkan';
      Helpers::log('Memvalidasi penyerahan '.str_replace('_',' ',$r->jenis).' menjadi '.$status);
        $update->update([$r->jenis=>$r->status]);

      return 'ok';
    }
    public function eksporExcel(Request $request)
    {
      $data=$this->dataUsulan($request)->get();

          return Excel::create('Rekap Data Penelitian PPM', function($excel) use ($data){
                $excel->sheet('Rekap',function($sheet)use($data){
                  $sheet->setOrientation('landscape');
                  $sheet->setStyle(array(
                      'font' => array(
                          'name'      =>  'Calibri',
                          'size'      =>  10,
                      )
                  ));
                  $sheet->setWidth('A',4);//NOMOR
                  $sheet->setWidth('B',6); // Tahun
                  $sheet->setWidth('C',13); // Jenis Kegiatan
                  $sheet->setWidth('D',20); // Unit Kerja
                  $sheet->setWidth('E',17); // Skim
                  $sheet->setWidth('F',17);// Jenis skema
                  $sheet->setWidth('G',4);// tkt
                  $sheet->setWidth('H',20);// Bidang
                  $sheet->setWidth('I',25);// Tema
                  $sheet->setWidth('J',43); // Judul
                  $sheet->setWidth('K',20);// Jumlah didanai
                  $sheet->setWidth('L',40);// Lokasi
                  $sheet->setWidth('M',20);// Tanggal Mulai
                  $sheet->setWidth('N',20);// Tanggal Selesai
                  $sheet->setWidth('O',40);// Luaran wajib
                  $sheet->setWidth('P',40);// Luaran tambahan
                  
                  $sheet->setWidth('Q',27); //nama ketua
                  $sheet->setWidth('R',13); // NIDN
                  $sheet->setWidth('S',27); // nama anggota
                  $sheet->setWidth('T',13); // NIDN anggota

                  $sheet->setWidth('U',10); // R1
                  $sheet->setWidth('V',14); // Rekomendasi
                  $sheet->setWidth('W',10); //R2
                  $sheet->setWidth('X',14); //Rekomendasi
                  $sheet->setWidth('Y',45); //catatan revis
                  $sheet->setWidth('Z',15); //waktu revisi
                  $sheet->setWidth('AA',10); // R1 Monev
                  $sheet->setWidth('AB',14); // Rekomendasi Monev
                  $sheet->setWidth('AC',10); //R2 Monev
                  $sheet->setWidth('AD',14); //Rekomendasi Monev

                  $sheet->setWidth('AE',15); //Download Proposal
                  $sheet->setWidth('AF',15); //Download Laporan Kemajuan
                  $sheet->setWidth('AG',15); //Download Laporan Akhir
                  $sheet->setWidth('AH',15); //Download Artikel
                  $sheet->setWidth('AI',15); //Download Luaran

                  $sheet->setWidth('AJ',15); //Hard Prop
                  $sheet->setWidth('AK',15); //Hard Lap. kemajuan
                  $sheet->setWidth('AL',15); //Hard Lap. Akhir
                  $sheet->setWidth('AM',15); //Hard Lap. artikel
                  $sheet->setWidth('AN',15); //Hard Lap. Luaran
                  
                  

                  //format kolom
                  $sheet->setColumnFormat([
                    'O' =>  '0000000000',
                    'R' =>  '0000000000',


                ]);

                  //heading tabel
                  $sheet->mergeCells('A1:A2');
                  $sheet->setCellValue('A1', 'No');
                  
                  $sheet->mergeCells('B1:B2');
                  $sheet->setCellValue('B1', 'Tahun');
                  
                  $sheet->mergeCells('C1:C2');
                  $sheet->setCellValue('C1', 'Jenis Kegiatan');

                  $sheet->mergeCells('D1:D2');
                  $sheet->setCellValue('D1', 'Unit Kerja');

                  $sheet->mergeCells('E1:E2');
                  $sheet->setCellValue('E1', 'Skim');

                  $sheet->mergeCells('F1:F2');
                  $sheet->setCellValue('F1', 'Jenis Skema');

                  $sheet->mergeCells('G1:G2');
                  $sheet->setCellValue('G1', 'TKT');

                  $sheet->mergeCells('H1:H2');
                  $sheet->setCellValue('H1', 'Bidang');

                  $sheet->mergeCells('I1:I2');
                  $sheet->setCellValue('I1', 'Tema');

                  $sheet->mergeCells('J1:J2');
                  $sheet->setCellValue('J1', 'Judul');

                  $sheet->mergeCells('K1:K2');
                  $sheet->setCellValue('K1', 'Jumlah Didanai');

                  $sheet->mergeCells('L1:L2');
                  $sheet->setCellValue('L1', 'Lokasi');

                  $sheet->mergeCells('M1:M2');
                  $sheet->setCellValue('M1', 'Tanggal Mulai');

                  $sheet->mergeCells('N1:N2');
                  $sheet->setCellValue('N1', 'Tanggal Selesai');

                  $sheet->mergeCells('O1:O2');
                  $sheet->setCellValue('O1', 'Luaran Wajib');

                  $sheet->mergeCells('P1:P2');
                  $sheet->setCellValue('P1', 'Luaran Tambahan');

                  $sheet->mergeCells('Q1:Q2');
                  $sheet->setCellValue('Q1', 'Nama Ketua');
                  $sheet->mergeCells('R1:R2');
                  $sheet->setCellValue('R1', 'NIDN/NIDK');

                  $sheet->mergeCells('S1:T1');
                  $sheet->setCellValue('S1', 'Anggota');
                  $sheet->setCellValue('S2', 'Nama');
                  $sheet->setCellValue('T2', 'NIDK/NIDN');

                  //popsoal
                  $sheet->mergeCells('U1:X1');
                  $sheet->setCellValue('U1', 'Penilaian Proposal');
                  $sheet->setCellValue('U2', 'R1');
                  $sheet->setCellValue('V2', 'Rekomendasi');
                  $sheet->setCellValue('W2', 'R2');
                  $sheet->setCellValue('X2', 'Rekomendasi');

                  $sheet->mergeCells('Y1:Y2');
                  $sheet->setCellValue('Y1', 'Catatan Revisi Proposal Oleh Pengusul');
                  $sheet->mergeCells('Z1:Z2');
                  $sheet->setCellValue('Z1', 'Waktu Revisi');

                  //monev
                  $sheet->mergeCells('AA1:AD1');
                  $sheet->setCellValue('AA1', 'Penilaian Monev');
                  $sheet->setCellValue('AA2', 'R1');
                  $sheet->setCellValue('AB2', 'Rekomendasi');
                  $sheet->setCellValue('AC2', 'R2');
                  $sheet->setCellValue('AD2', 'Rekomendasi');
                  
                  //laporan
                  $sheet->mergeCells('AE1:AE2');
                  $sheet->setCellValue('AE1', 'Proposal');
                  $sheet->mergeCells('AF1:AF2');
                  $sheet->setCellValue('AF1', 'Laporan Kemajuan');
                  $sheet->mergeCells('AG1:AG2');
                  $sheet->setCellValue('AG1', 'Laporan Akhir');
                  $sheet->mergeCells('AH1:AH2');
                  $sheet->setCellValue('AH1', 'Artikel');
                  $sheet->mergeCells('AI1:AI2');
                  $sheet->setCellValue('AI1', 'Luaran');

                  //hard
                  $sheet->mergeCells('AJ1:AJ2');
                  $sheet->setCellValue('AJ1', 'Hard. Prop');
                  $sheet->mergeCells('AK1:AK2');
                  $sheet->setCellValue('AK1', 'Hard. Lap.Kemajuan');
                  $sheet->mergeCells('AL1:AL2');
                  $sheet->setCellValue('AL1', 'Hard. Lap.Akhir');
                  $sheet->mergeCells('AM1:AM2');
                  $sheet->setCellValue('AM1', 'Hard. Artikel');
                  $sheet->mergeCells('AN1:AN2');
                  $sheet->setCellValue('AN1', 'Hard. Luaran');

                  $sheet->getStyle('A1:AN2')->getAlignment()->setWrapText(true);
                  $sheet->cells("A1:AN2", function($cells) {
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
                      $sheet->setCellValue('B'.$startrow,$ob->buka_penerimaan->tahun_anggaran->tahun);

                      $sheet->mergeCells('C'.$startrow.':C'.$mergeto);
                      $sheet->setCellValue('C'.$startrow,$ob->buka_penerimaan->jenis_usulan->jenis_usulan);

                      $sheet->mergeCells('D'.$startrow.':D'.$mergeto);
                      $sheet->setCellValue('D'.$startrow,$ob->buka_penerimaan->unit_kerja->nama_unit);

                      $sheet->mergeCells('E'.$startrow.':E'.$mergeto);
                      $sheet->setCellValue('E'.$startrow,$ob->buka_penerimaan->skim->nama_skim);

                      $sheet->mergeCells('F'.$startrow.':F'.$mergeto);
                      $sheet->setCellValue('F'.$startrow,$ob->jenis_skema->nama_jenis_skema);

                      $sheet->mergeCells('G'.$startrow.':G'.$mergeto);
                      $sheet->setCellValue('G'.$startrow,$ob->tkt->nilai_tkt);

                      $sheet->mergeCells('H'.$startrow.':H'.$mergeto);
                      $sheet->setCellValue('H'.$startrow,$ob->tema->bidang->nama_bidang);

                      $sheet->mergeCells('I'.$startrow.':I'.$mergeto);
                      $sheet->setCellValue('I'.$startrow,$ob->tema->nama_tema);

                      $sheet->mergeCells('J'.$startrow.':J'.$mergeto);
                      $sheet->setCellValue('J'.$startrow,htmlentities($ob->judul));

                      $sheet->mergeCells('K'.$startrow.':K'.$mergeto);
                      
                      if (auth()->check()) {  
                        $sheet->setCellValue('K'.$startrow,Uang::format_uang($ob->dana_perjudul));
                      }

                      $sheet->mergeCells('L'.$startrow.':L'.$mergeto);
                      $sheet->setCellValue('L'.$startrow,Helpers::lokasi($ob));

                      $sheet->mergeCells('M'.$startrow.':M'.$mergeto);
                      $sheet->setCellValue('M'.$startrow,Tanggal::tgl_indo($ob->tanggal_mulai));

                      $sheet->mergeCells('N'.$startrow.':N'.$mergeto);
                      $sheet->setCellValue('N'.$startrow,Tanggal::tgl_indo($ob->tanggal_selesai));

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

                      $sheet->mergeCells('O'.$startrow.':O'.$mergeto);
                      $sheet->setCellValue('O'.$startrow,$wajib);

                      $sheet->mergeCells('P'.$startrow.':P'.$mergeto);
                      $sheet->setCellValue('P'.$startrow,$tambahan);

                      $sheet->mergeCells('Q'.$startrow.':Q'.$mergeto);
                      $sheet->setCellValue('Q'.$startrow,Helpers::nama_gelar($ob->ketua->pegawai));

                      $sheet->mergeCells('R'.$startrow.':R'.$mergeto);
                      $sheet->setCellValue('R'.$startrow,$ob->ketua->pegawai->nip." ");

                      foreach ($ob->anggota as $no => $anggota) {

                          $sheet->setCellValue('S'.$startrow2,Helpers::nama_gelar($anggota->pegawai));
                          $sheet->setCellValue('T'.$startrow2,$anggota->pegawai->nip." ");
                          $startrow2++;
                          
                      }
                      //proposal
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
                        
                      if (auth()->check()) {
                        
                        $sheet->mergeCells('U'.$startrow.':U'.$mergeto);
                        $sheet->setCellValue('U'.$startrow,$nilai);
                        $sheet->mergeCells('V'.$startrow.':V'.$mergeto);
                        $sheet->setCellValue('V'.$startrow,$rekomendasi);

                      }
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
                        
                      if (auth()->check()) {
                        $sheet->mergeCells('W'.$startrow.':W'.$mergeto);
                        $sheet->setCellValue('W'.$startrow,$nilai);
                        $sheet->mergeCells('X'.$startrow.':X'.$mergeto);
                        $sheet->setCellValue('X'.$startrow,$rekomendasi);
                      }
                      $revisi_proposal='';
                      $tgl_revisi_proposal='-';
                      
                      if ($ob->waktu_ajukan_revisi_proposal!=null) {
                        $revisi_proposal=$ob->catatan_revisi_proposal;
                        $tgl_revisi_proposal=Tanggal::time_indo($ob->waktu_ajukan_revisi_proposal);
                      }

                      if (auth()->check()) {
                        $sheet->mergeCells('Y'.$startrow.':Y'.$mergeto);
                        $sheet->setCellValue('Y'.$startrow,$revisi_proposal);
                        
                        $sheet->mergeCells('Z'.$startrow.':Z'.$mergeto);
                        $sheet->setCellValue('Z'.$startrow,$tgl_revisi_proposal);
                      }
                      //hasil
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
                        
                      if (auth()->check()) {
                        $sheet->mergeCells('AA'.$startrow.':AA'.$mergeto);
                        $sheet->setCellValue('AA'.$startrow,$nilai);
                        $sheet->mergeCells('AB'.$startrow.':AB'.$mergeto);
                        $sheet->setCellValue('AB'.$startrow,$rekomendasi);
                      }
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
                        
                      if (auth()->check()) {
                        $sheet->mergeCells('AC'.$startrow.':AC'.$mergeto);
                        $sheet->setCellValue('AC'.$startrow,$nilai);
                        $sheet->mergeCells('AD'.$startrow.':AD'.$mergeto);
                        $sheet->setCellValue('AD'.$startrow,$rekomendasi);
                      }
                      $sheet->mergeCells('AE'.$startrow.':AE'.$mergeto);
                      $sheet->mergeCells('AF'.$startrow.':AF'.$mergeto);
                      $sheet->mergeCells('AG'.$startrow.':AG'.$mergeto);
                      $sheet->mergeCells('AH'.$startrow.':AH'.$mergeto);
                      $sheet->mergeCells('AI'.$startrow.':AI'.$mergeto);
                      $sheet->mergeCells('AJ'.$startrow.':AJ'.$mergeto);
                      $sheet->mergeCells('AK'.$startrow.':AK'.$mergeto);
                      $sheet->mergeCells('AL'.$startrow.':AL'.$mergeto);
                      $sheet->mergeCells('AM'.$startrow.':AM'.$mergeto);
                      $sheet->mergeCells('AN'.$startrow.':AN'.$mergeto);
                      if (auth()->check()) {
                        //laporan
                        if ($ob->file_proposal!=null||$ob->file_proposal!="") {
                          $sheet->getCell('AE'.$startrow)
                              ->setValueExplicit("Download", \PHPExcel_Cell_DataType::TYPE_STRING)
                              ->getHyperlink()
                              ->setUrl(url('/dokumen/proposal/' . $ob->file_proposal))
                              ->setTooltip('Download');
                        }else{
                          $sheet->setCellValue('AE'.$startrow,'Belum Upload');
                        }
                        if ($ob->file_laporan_kemajuan!=null||$ob->file_laporan_kemajuan!="") {
                          $sheet->getCell('AF'.$startrow)
                              ->setValueExplicit("Download", \PHPExcel_Cell_DataType::TYPE_STRING)
                              ->getHyperlink()
                              ->setUrl(url('/dokumen/laporan-kemajuan/'.$ob->file_laporan_kemajuan))
                              ->setTooltip('Download');
                        }else{
                          $sheet->setCellValue('AF'.$startrow,'Belum Upload');
                        }
                        if ($ob->file_laporan_akhir!=null||$ob->file_laporan_akhir!="") {
                          $sheet->getCell('AG'.$startrow)
                              ->setValueExplicit("Download", \PHPExcel_Cell_DataType::TYPE_STRING)
                              ->getHyperlink()
                              ->setUrl(url('/dokumen/laporan-akhir/' . $ob->file_laporan_akhir))
                              ->setTooltip('Download');
                        }else{
                          $sheet->setCellValue('AG'.$startrow,'Belum Upload');
                        }
                        if ($ob->file_artikel!=null||$ob->file_artikel!="") {
                          $sheet->getCell('AH'.$startrow)
                              ->setValueExplicit("Download", \PHPExcel_Cell_DataType::TYPE_STRING)
                              ->getHyperlink()
                              ->setUrl(url('/dokumen/artikel/' . $ob->file_artikel))
                              ->setTooltip('Download');
                        }else{
                          $sheet->setCellValue('AH'.$startrow,'Belum Upload');
                        }

                        if ($ob->file_luaran!=null||$ob->file_luaran!="") {
                          $sheet->getCell('AI'.$startrow)
                              ->setValueExplicit("Download", \PHPExcel_Cell_DataType::TYPE_STRING)
                              ->getHyperlink()
                              ->setUrl(url('/dokumen/luaran/' . $ob->file_luaran))
                              ->setTooltip('Download');
                        }else{
                          $sheet->setCellValue('AI'.$startrow,'Belum Upload');
                        }

                        if ($ob->hard_proposal==0) $sheet->setCellValue('AJ'.$startrow,'Belum Ada');
                        else  $sheet->setCellValue('AJ'.$startrow,'Ada');

                        if ($ob->hard_laporan_kemajuan==0) $sheet->setCellValue('AK'.$startrow,'Belum Ada');
                        else  $sheet->setCellValue('AK'.$startrow,'Ada');

                        if ($ob->hard_laporan_akhir==0) $sheet->setCellValue('AL'.$startrow,'Belum Ada');
                        else  $sheet->setCellValue('AL'.$startrow,'Ada');
                        if ($ob->hard_artikel==0) $sheet->setCellValue('AM'.$startrow,'Belum Ada');
                        else  $sheet->setCellValue('AM'.$startrow,'Ada');
                        if ($ob->hard_luaran==0) $sheet->setCellValue('AN'.$startrow,'Belum Ada');
                        else  $sheet->setCellValue('AN'.$startrow,'Ada');
                      }

                      $startrow=$startrow+$ob->jumlah_anggota;    
                      $end=$startrow -1;
                      $sheet->setBorder('A1:AN'.$end, 'thin');
                      $sheet->getStyle('A3:AN'.$startrow)->getAlignment()->setWrapText(true);
                      
                    
                    $sheet->cells("A3:A".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('center');
                      });
                    $sheet->cells("B3:T".$startrow, function($cells) {
                            $cells->setValignment('center');
                            
                    });
                      $sheet->cells("T3:X".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('center');
                      });
                      $sheet->cells("Y3:Z".$startrow, function($cells) {
                            $cells->setValignment('center');
                            
                      });
                      $sheet->cells("AA3:AD".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('center');
                      });
                      
                      $sheet->cells("AE3:AN".$startrow, function($cells) {
                            $cells->setValignment('center');
                            $cells->setAlignment('center');
                      });
                      

                      $sheet->cells('AE3:AI'.$startrow,function($cells){
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                        $cells->setFontColor('#0000FF');
                      });
                      

                  }
                 
                });

                 
               
               
          })->download('xlsx');
    }
    
    

   
    

}
