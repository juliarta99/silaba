<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/aboutPage', 'aboutPage')->name('aboutPage');
Route::view('/formulirRating', 'formulirRating')->name('formulirRating');
Route::view('/ratingBerhasilDikirim', 'ratingBerhasilDikirim')->name('ratingBerhasilDikirim');
Route::view('/kelolaInstansiKadis', 'kelolaInstansiKadis')->name('kelolaInstansiKadis');
Route::view('/kelolaInstansiSupervisor', 'kelolaInstansiSupervisor')->name('kelolaInstansiSupervisor');
