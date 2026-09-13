<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LahanController;
use App\Http\Controllers\LahanPickerController;
use App\Http\Controllers\PanenCycleController;
use App\Http\Controllers\PartnerAgreementController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgressLogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TroubleReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Halaman pilih lahan — di luar middleware active-lahan (ini yang menentukannya)
    Route::get('pilih-lahan', [LahanPickerController::class, 'index'])->name('lahan-picker.index');
    Route::post('pilih-lahan/{lahan}', [LahanPickerController::class, 'select'])->name('lahan-picker.select');
    Route::post('pilih-lahan-semua', [LahanPickerController::class, 'selectAll'])->name('lahan-picker.select-all');

    // Modul lintas-lahan — tidak butuh active-lahan
    Route::resource('lahans', LahanController::class);
    Route::resource('partners', PartnerController::class);
    Route::resource('partner-agreements', PartnerAgreementController::class)->except(['index', 'show']);
    Route::resource('assets', AssetController::class);
    Route::get('reports', [ReportController::class, 'form'])->name('reports.form');
    Route::post('reports/generate', [ReportController::class, 'generate'])->name('reports.generate');

    // Dashboard & modul operasional — pakai active-lahan
    Route::middleware('active-lahan')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware('verified')
            ->name('dashboard');

        Route::resource('transactions', TransactionController::class);
        Route::resource('progress-logs', ProgressLogController::class);
        Route::resource('trouble-reports', TroubleReportController::class);
        Route::post('trouble-reports/{troubleReport}/advance-status', [TroubleReportController::class, 'advanceStatus'])
            ->name('trouble-reports.advance-status');
        Route::post('trouble-reports/{troubleReport}/updates', [TroubleReportController::class, 'addUpdate'])
            ->name('trouble-reports.add-update');

        Route::resource('schedules', ScheduleController::class);
        Route::post('schedules/{schedule}/mark-done', [ScheduleController::class, 'markDone'])
            ->name('schedules.mark-done');

        Route::resource('panen-cycles', PanenCycleController::class)->except(['edit', 'update']);
    });
});

require __DIR__ . '/auth.php';
