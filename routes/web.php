<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('newcreature', function () {
    return view('builder.newCreature');
});

Route::get('randomize', function () {
    return view('builder.randomize');
});

Route::get('encounter', function () {
    return view('builder.encounter');
});

Route::get('creature', function () {
    return view('builder.creature');
});