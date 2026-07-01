<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FarmController;

Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('/dashboard', function () {
    return redirect()->route('farms.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // 1. Route Statis ditaruh DI ATAS
    Route::get('/dashboard-data', [FarmController::class, 'dashboard'])->name('farms.dashboard');
    Route::get('/farms', [FarmController::class, 'index'])->name('farms.index');
    Route::get('/farms-export', [FarmController::class, 'export'])->name('farms.export');
    Route::get('/farms/about-model', [FarmController::class, 'aboutModel'])->name('farms.about-model');
    
    // Fitur Compare
    Route::get('/farms/compare', [FarmController::class, 'compareForm'])->name('farms.compare.form');
    Route::post('/farms/compare', [FarmController::class, 'compareResult'])->name('farms.compare.result');

    // 2. Khusus Admin
    Route::middleware(['admin'])->group(function () {
        Route::get('/farms/trashed', [FarmController::class, 'trashed'])->name('farms.trashed');
        Route::get('/farms/create', [FarmController::class, 'create'])->name('farms.create');
        Route::post('/farms', [FarmController::class, 'store'])->name('farms.store');
        Route::get('/farms/{farm}/edit', [FarmController::class, 'edit'])->name('farms.edit');
        Route::put('/farms/{farm}', [FarmController::class, 'update'])->name('farms.update');
        Route::delete('/farms/{farm}', [FarmController::class, 'destroy'])->name('farms.destroy');
        Route::patch('/farms/{id}/restore', [FarmController::class, 'restore'])->name('farms.restore');
        Route::delete('/farms/{id}/force-delete', [FarmController::class, 'forceDelete'])->name('farms.force-delete');
    });

    // 3. Route Wildcard (Dinamis) ditaruh PALING BAWAH
    Route::get('/farms/{farm}', [FarmController::class, 'show'])->name('farms.show');
});

require __DIR__.'/auth.php';
