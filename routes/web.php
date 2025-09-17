<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware('HasLoggenIn')->name('dashboard');

require __DIR__.'/auth.php';



