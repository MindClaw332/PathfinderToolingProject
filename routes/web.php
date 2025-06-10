<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuilderController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/builder/encounter', [BuilderController::class, 'encounter'])->name('builder.encounter');
Route::get('/builder/randomize', [BuilderController::class, 'randomize'])->name('builder.randomize');
Route::get('/builder/newcreature', [BuilderController::class, 'newcreature'])->name('builder.newcreature');

Route::get('builder/creature', [BuilderController::class, 'creature'])->name('builder.creature');

Route::post('/content/{content}/creatures', [BuilderController::class, 'addCreature']);