<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/inspections', function() {
    return view('inspection-form');
})->name('inspections.create');


Route::get('/test-page', function() {
    return '<h1>کار میکنه</h1>';
});



Route::get('/inspection-form', function () {
    return view('inspection-form');
})->name('inspection.form');

require __DIR__.'/auth.php';