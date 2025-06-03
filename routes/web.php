<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('newcreature', function() {
    return view('newCreature');
});

Route::get('randomize', function() {
    return view('randomize');
});
