<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\UnlockAccountController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // routes for unlocking an account
    Route::get('unlock-account/{hash}', [UnlockAccountController::class, 'show'])->name('unlock-account.confirm')
        ->middleware(['signed', 'throttle:10,1']);
    Route::post('unlock-account', [UnlockAccountController::class, 'store'])->name('unlock-account.verify');
});

Route::middleware('auth')->group(function () {
    // Email verification routes
    Route::controller(VerifyEmailController::class)->group(function () {
        Route::get('verify-email', 'show')->name('verification.notice');
        Route::get('verify-email/{hash}', 'update')->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
        Route::post('email/verification-notification', 'store')->middleware('throttle:6,1')->name('verification.send');
    });

    /**
     * Protected routed that only verified user can access.
     */
    Route::middleware('verified')->group(function () {
        Route::get('confirm-password', [ConfirmPasswordController::class, 'show'])->name('password.confirm');
        Route::post('confirm-password', [ConfirmPasswordController::class, 'store']);
        Route::put('password', [PasswordController::class, 'update'])->name('password.update');
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });

});
