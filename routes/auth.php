<?php
Route::get('pengaturan-akun','AuthController@pengaturanAkun');
Route::post('pengaturan-akun','AuthController@postPengaturanAkun');
Route::get('logout','AuthController@logout');
Route::post('login','AuthController@cekLogin');
Route::get('lupa-password','AuthController@lupaPassword');
Route::post('lupa-password','AuthController@resetPassword');
Route::get('proses-reset-password/{id}','AuthController@prosesResetPassword');