<?php

Route::get('php-info',function(){
	echo phpinfo();
});
Route::get('/','HomeController@index');
Route::get('public/detail-info/{id}','HomeController@detailInfo');
Route::get('public/rekap-data','HomeController@rekapData');

Route::post('public/rekap-data','\App\Http\Controllers\Admin\LaporanKegiatanController@eksporExcel');
Route::get('load-dosen-pegawai-public','HomeController@loadDosenPegawai');

