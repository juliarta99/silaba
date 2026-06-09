<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::view('/login', 'login')->name('login');
Route::view('/Dashboard-Saya', 'Dashboard-Saya')->name('Dashboard-Saya');

// nah kayak gitu, untuk parameter pertama itu urlnya contoh /register atau /login (jangan isi spasi)
// parameter kedua itu nama filenya (karena ak buatnya login.blade.php jadi isiinya login)
// untuk name sesuaiin aja jangan isi spasi
// lanjutt coba buka dan akses link tersebut
