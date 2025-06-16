<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuilderController;
use App\Http\Controllers\CombatController;
use App\Http\Controllers\CreatureController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/combat', [CombatController::class, 'index'])->name('combat');
Route::get('/creatures/search', [CreatureController::class, 'search'])->name('creatures.search');

Route::get('newcreature', [BuilderController::class, 'newcreature']);
Route::get('randomize', [BuilderController::class, 'randomize']);
Route::get('encounter', [BuilderController::class, 'encounter']);
Route::get('creature', [BuilderController::class, 'creature']);