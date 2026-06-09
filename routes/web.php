<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/otp-wa', 'Verifikasi-OTP-WA')->name('Verifikasi-OTP-WA');
Route::view('/klaim-reward', 'klaim-reward')->name('klaim-reward');