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
Route::put('/content/{content}/creatures/{index}', [BuilderController::class, 'updateCreature']);
Route::delete('/content/{content}/creatures/{index}', [BuilderController::class, 'removeCreature']);

Route::post('/content/{content}/hazards', [BuilderController::class, 'addHazard']);
Route::delete('/content/{content}/hazards/{index}', [BuilderController::class, 'removeHazard']);

Route::post('/content/{content}/calculate', [BuilderController::class, 'calculateXP']);