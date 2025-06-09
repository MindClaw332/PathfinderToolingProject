<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuilderController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/combat', function () {
    return view('combat');
});

Route::get('newcreature', [BuilderController::class, 'newcreature']);

Route::get('randomize', [BuilderController::class, 'randomize']);

Route::get('encounter', [BuilderController::class, 'encounter']);

Route::get('creature', [BuilderController::class, 'creature']);