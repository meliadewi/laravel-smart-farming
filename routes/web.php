<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FarmController;

Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::middleware(['auth'])->group(function () {
    Route::resource('farms', FarmController::class);
});

require __DIR__.'/auth.php';

Route::get('/dashboard', function () {
    return redirect()->route('farms.index');
})->middleware(['auth'])->name('dashboard');
