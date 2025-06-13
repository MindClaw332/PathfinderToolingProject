<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuilderController;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/builder/encounter', [BuilderController::class, 'encounter'])->name('builder.encounter');
Route::get('/builder/randomize', [BuilderController::class, 'randomize'])->name('builder.randomize');
Route::get('/builder/newcreature', [BuilderController::class, 'newcreature'])->name('builder.newcreature');

Route::get('builder/creature', [BuilderController::class, 'creature'])->name('builder.creature');

Route::post('/content/{content}/creatures', [BuilderController::class, 'addCreature']);
Route::delete('/content/{content}/creatures/{index}', [BuilderController::class, 'removeCreature']);


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('login.logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register.register');


Route::get('/login/test', function () {
    return view('auth.authtest');
})->middleware('auth');
