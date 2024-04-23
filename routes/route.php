<?php

//ajax
Route::get('load-dosen-pegawai','LoadDataController@loadDosenPegawai');
Route::get('cek-dosen-tersedia','LoadDataController@cekDosenTersedia');
Route::get('load-tema/{id}','LoadDataController@loadTema');
Route::get('load-luaran-tkt/{id}','LoadDataController@loadLuaranTKT');
Route::get('load-kecamatan','LoadDataController@loadKecamatan');
Route::get('load-data-reviewer','LoadDataController@loadDataReviewer');


//operator
//dashboard
Route::get('dashboard','DashboardController@index');


//master data
Route::resource('master-unit-kerja','MasterUnitKerjaController');
Route::resource('master-sumber-dana','MasterSumberDanaController');
Route::resource('master-skim','MasterSkimController');
Route::resource('master-bidang-tema','MasterBidangTemaController');
Route::get('master-bidang-tema/hapus-tema/{id}','MasterBidangTemaController@hapusTema');
Route::resource('master-luaran','MasterLuaranController');
Route::resource('master-jenis-skema','MasterJenisSkemaController');

//setup aplikasi
//tahun
Route::resource('setup-tahun-penerimaan','SetupTahunPenerimaanController');
//setup
Route::get('setup-tahun-penerimaan/aktifkan/{id}','SetupTahunPenerimaanController@aktifkan');
Route::put('setup-aplikasi/{id}','SetupTahunPenerimaanController@ubahSetup');
//reviewer
Route::resource('setup-reviewer','SetupReviewerController');
//borang
Route::resource('setup-borang','SetupBorangController');
Route::get('setup-borang/input-borang/{tahap}/{id}','SetupBorangController@inputBorang');
Route::get('setup-borang/input-borang/{tahap}/{id}/create','SetupBorangController@createInputBorang');
Route::post('setup-borang/input-borang/{tahap}/{id}','SetupBorangController@storeInputBorang');
Route::get('setup-borang/input-borang/{tahap}/{id_skema}/{id_borang}/edit','SetupBorangController@editInputBorang');
Route::put('setup-borang/input-borang/{tahap}/{id_skema}/{id_borang}','SetupBorangController@updateInputBorang');
Route::delete('setup-borang/input-borang/{tahap}/{id_skema}/{id_borang}','SetupBorangController@destroyInputBorang');
Route::get('delete-skor-borang/{id}','SetupBorangController@destroySkorBorang');

//blokir
Route::resource('blokir','BlokirController');

//buka penerimaan
Route::resource('buka-penerimaan','BukaPenerimaanController');

//seleksi usulan
Route::get('seleksi-usulan','SeleksiUsulanController@index');
Route::get('seleksi-usulan/{id}','SeleksiUsulanController@show');
Route::post('seleksi-usulan/ekspor-excel','SeleksiUsulanController@excelReviewProposal');
Route::post('seleksi-usulan/input-reviewer','SeleksiUsulanController@storeReviewerProposal');
Route::get('seleksi-usulan/hapus-reviewer/{id}','SeleksiUsulanController@destroyReviewer');
Route::post('seleksi-usulan/submit-proses-usulan','SeleksiUsulanController@submitProsesUsulan');
Route::get('seleksi-usulan/edit/{id}/{admin}','PendaftaranUsulanController@edit');


//monitoring dan evaluasi
Route::get('monitoring-dan-evaluasi','MonitoringDanEvaluasiController@index');
Route::get('monitoring-dan-evaluasi/{id}','MonitoringDanEvaluasiController@show');
Route::post('monitoring-dan-evaluasi/ekspor-excel','MonitoringDanEvaluasiController@excelReviewMonev');
Route::post('monitoring-dan-evaluasi/input-reviewer','MonitoringDanEvaluasiController@storeReviewerMonev');
Route::get('monitoring-dan-evaluasi/hapus-reviewer/{id}','MonitoringDanEvaluasiController@destroyReviewer');

//laporan kegiatan
Route::get('laporan-kegiatan','LaporanKegiatanController@index');
Route::get('laporan-kegiatan/{id}','LaporanKegiatanController@show');
Route::post('laporan-kegiatan/ceklis','LaporanKegiatanController@ceklisHardCopy');
Route::post('laporan-kegiatan/ekspor-excel','LaporanKegiatanController@eksporExcel');
// Route::post('monitoring-dan-evaluasi/ekspor-excel','MonitoringDanEvaluasiController@excelReviewMonev');
// Route::post('monitoring-dan-evaluasi/input-reviewer','MonitoringDanEvaluasiController@storeReviewerMonev');
// Route::get('monitoring-dan-evaluasi/hapus-reviewer/{id}','MonitoringDanEvaluasiController@destroyReviewer');


//artikel
Route::resource('info-pendaftaran','InfoPendaftaranController');
Route::post('file-manager-direct','FileManagerController@directUpload');
Route::resource('file-manager-upload','FileManagerController');


//dosen
Route::get('dashboard-dosen','DashboardController@indexDosen');
Route::resource('data-diri','DataDiriController');
Route::get('data-umum/{id}','DataDiriController@dataUmum');
Route::get('pendaftaran-usulan','PendaftaranUsulanController@index');
Route::get('pendaftaran-usulan/{id_jenis}','PendaftaranUsulanController@buatUsulan');
Route::post('pendaftaran-usulan/{id_jenis}','PendaftaranUsulanController@store');
Route::get('pendaftaran-usulan/detail/{id}','PendaftaranUsulanController@detail');

Route::get('pendaftaran-usulan/input-anggota/{id}','PendaftaranUsulanController@inputAnggota');
Route::post('pendaftaran-usulan/input-anggota/{id}','PendaftaranUsulanController@postInputAnggota');

Route::delete('pendaftaran-usulan/delete/{id}','PendaftaranUsulanController@destroy');
Route::get('pendaftaran-usulan/edit/{id}','PendaftaranUsulanController@edit');
Route::put('pendaftaran-usulan/edit/{id}','PendaftaranUsulanController@update');
Route::get('pendaftaran-usulan/ajukan/{id}','PendaftaranUsulanController@ajukan');

//penelitian dan ppm saya

Route::get('penelitian-ppm-saya','PenelitianPPMSayaController@index');
Route::get('penelitian-ppm-saya/detail/{id}','PenelitianPPMSayaController@detail');
Route::get('penelitian-ppm-saya/laporan/{id}','PenelitianPPMSayaController@laporan');
Route::post('penelitian-ppm-saya/post/{laporan}/{id}','PenelitianPPMSayaController@postLaporan');
Route::get('penelitian-ppm-saya/hapus-laporan/{laporan}/{id}','PenelitianPPMSayaController@destroyLaporan');

//penelitian dan ppm saya

Route::get('ekspor-excel-ppm-perdosen/{id}','RekapExcelController@rekapPerdosen');

//konfirmasi
Route::get('konfirmasi','KonfirmasiController@index');
Route::get('konfirmasi/{id}/{konfirmasi}','KonfirmasiController@konfirmasi');


//reviewer
Route::get('review/{jenis}','ReviewerController@index');
Route::get('review/{jenis}/proses-review/{id}','ReviewerController@prosesReview');


Route::post("review/borang/save-skor","ReviewerController@saveSkor");
Route::post("review/borang/save-review/{id_usulan}","ReviewerController@saveReview");
Route::get("review/download-review/{id}","ReviewerController@downloadReview");
Route::get("review/batalkan-review/{id}","ReviewerController@batalkanReview");




//user role menu
Route::resource('manage-user','UserController');
Route::get('manage-user/{id}/hapus','UserController@destroy');
Route::resource('manage-role','RoleController');
Route::resource('manage-menu','MenuController');
Route::post('manage-menu/create-permission','MenuController@createPermission');

//setting halaman depan
// Route::resource('setting-carousel','CarouselController');
// Route::resource('setting-step','StepController');
Route::resource('setting-konten-statis','KontenStatisController');
Route::get('log-aktivitas','LogAktivitasController@index');
Route::get('log-aktivitas/hapus/{id}','LogAktivitasController@destroy');
Route::get('unauthorized',function(){
  echo session('noakses');
});

Route::get('get-bidang',function(){

	$b=DB::table('simlppm3.bidang')->where('tahun_anggaran_id',8)->get();
	foreach ($b as $k) {
		$u=App\Bidang::create([
			'tahun_anggaran_id'=>1,
			'nama_bidang'=>$k->bidang,
			'jenis_usulan_id'=>$k->jenis_usulan_id
		]);
		$z=DB::table('simlppm3.tema')->where('bidang_id',$k->id)->get();
		foreach ($z as $x) {
			$h=App\Tema::create([
				'bidang_id'=>$u->id_bidang,
				'nama_tema'=>$x->tema,
				
			]);
		}
	}
});
Route::get('get-luaran',function(){

	$b=DB::table('simlppm3.jenis_luaran')->get();
	foreach ($b as $k) {
		$u=App\Luaran::create([
			
			'nama_luaran'=>$k->nama_jenis_luaran,
			
		]);
	}
});