<?php

use Illuminate\Support\Facades\Route;

// Homepage (Blade)
Route::get('/', function () {
    return view('welcome');
});

// Pagina about (Blade)
Route::get('/about', function () {
    return view('about');
});

// Rotta protetta (middleware auth)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
});