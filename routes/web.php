<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('verifikasi-otp-wa', 'verifikasi-otp-wa')->name('verifikasi-otp-wa');
Route::view('verifikasi-otp-wa-berhasil', 'verifikasi-otp-wa-berhasil')->name('verifikasi-otp-wa-berhasil');
Route::view('klaim-reward', 'klaim-reward')->name('klaim-reward');