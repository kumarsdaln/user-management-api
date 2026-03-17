<?php

use App\Http\Controllers\API\AnalysePDFController;
use App\Http\Controllers\Api\AuditLogController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\API\UploadPDFController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:login')->post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware('throttle:reset-password')->prefix('reset-password')->group(function () {
    Route::post('/', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::post('/confirm', [AuthController::class, 'confirmResetPassword'])->name('password.confirm');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::prefix('users')->middleware('role_or_permission:admin|manage_users', 'throttle:users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::post('/create', [UserController::class, 'store'])->name('users.create');
        Route::put('/{user:id}/update', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user:id}/delete', [UserController::class, 'delete'])->name('users.delete');
    });

    Route::prefix('pdf')->group(function () {
        Route::post('start', [UploadPDFController::class, 'start']);
        Route::get('{pdf:id}/status', [UploadPDFController::class, 'status']);
        Route::get('{pdf:id}/result', [UploadPDFController::class, 'result']);
    });
});
Route::prefix('pdf')->group(function () {
    Route::post('api1', [AnalysePDFController::class, 'step1']);
    Route::post('api2', [AnalysePDFController::class, 'step2']);
    Route::post('api3', [AnalysePDFController::class, 'step3']);
});

Route::middleware(['auth:sanctum','role:admin', 'throttle:audit-log'])->group(function () {
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
});