<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/goals', [GoalController::class, 'index'])->name('goals.index');
    Route::get('/goals/create', [GoalController::class, 'create'])->name('goals.create');
    Route::post('/goals', [GoalController::class, 'store'])->name('goals.store');
    Route::post('/goals/{goal}/complete', [GoalController::class, 'markComplete'])->name('goals.complete');
    Route::get('/goals/export/pdf', [GoalController::class, 'exportPdf'])->name('goals.export.pdf');
    Route::get('/goals/export/csv', [GoalController::class, 'exportCsv'])->name('goals.export.csv');});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');


// Route::middleware(['auth'])->group(function () {
//     Route::get('/export/csv', [ExportController::class, 'csv'])->name('export.csv');
//     Route::get('/export/pdf', [ExportController::class, 'pdf'])->name('export.pdf');
// });


require __DIR__.'/auth.php';
