<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::view('/laporan', 'List-Laporan-SKC')->name('laporan');

// nah kayak gitu, untuk parameter pertama itu urlnya contoh /register atau /login (jangan isi spasi)
// parameter kedua itu nama filenya (karena ak buatnya login.blade.php jadi isiinya login)
// untuk name sesuaiin aja jangan isi spasi
// lanjutt coba buka dan akses link tersebut
