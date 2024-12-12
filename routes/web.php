<?php

declare(strict_types=1);

use App\Http\Controllers\TransferController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SendMoneyController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/recursive-transfer', [DashboardController::class, 'recurringTransfer'])->name('transfer');
    Route::post('/send-money', [SendMoneyController::class, '__invoke'])->name('send-money');
    Route::post('/create-transfer', [TransferController::class, '__invoke'])->name('create-transfer');

});

require __DIR__.'/auth.php';
