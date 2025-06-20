<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuilderController;
use App\Http\Controllers\CombatController;
use App\Http\Controllers\CreatureController;
use App\Http\Controllers\VerificationController;
use App\Http\Middleware\CustomAuth;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use Illuminate\Http\Request;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/builder/encounter', [BuilderController::class, 'encounter'])->name('builder.encounter');
Route::get('/builder/randomize', [BuilderController::class, 'randomize'])->name('builder.randomize');
Route::get('/builder/newcreature', [BuilderController::class, 'newcreature'])->name('builder.newcreature');

Route::get('builder/creature', [BuilderController::class, 'creature'])->name('builder.creature');

Route::post('/content/{content}/creatures', [BuilderController::class, 'addCreature']);

Route::delete('/content/{content}/creatures/{index}', [BuilderController::class, 'removeCreature']);
// authentication flow
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register.register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('login.logout');
//verification flow
Route::get('/email/verify', [VerificationController::class, 'showReVerificationForm'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verifyEmail'])->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', [VerificationController::class, 'sendVerificationEmail'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/combat', [CombatController::class, 'index'])->name('combat');
Route::get('/creatures/search', [CreatureController::class, 'search'])->name('creatures.search');

// Add PDF generation route
Route::post('/generate-combat-pdf', [CombatController::class, 'generatePDF'])
    ->name('generate.combat.pdf');

Route::get('newcreature', [BuilderController::class, 'newcreature']);
Route::get('randomize', [BuilderController::class, 'randomize']);
Route::get('encounter', [BuilderController::class, 'encounter']);
Route::get('creature', [BuilderController::class, 'creature']);

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/login/test', function () {
    return view('auth.authtest');
})->middleware([CustomAuth::class, 'verified']);

Route::get('/donate', function () {
    return view('donate');
})->name('donate');
Route::put('/content/{content}/creatures/{index}', [BuilderController::class, 'updateCreature']);
Route::delete('/content/{content}/creatures/{index}', [BuilderController::class, 'removeCreature']);
Route::post('/content/{content}/hazards', [BuilderController::class, 'addHazard']);
Route::delete('/content/{content}/hazards/{index}', [BuilderController::class, 'removeHazard']);
Route::post('/content/{content}/calculate', [BuilderController::class, 'calculateXP']);
