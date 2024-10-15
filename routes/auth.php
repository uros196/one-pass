<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');

    Route::get('verify-email/{hash}', VerifyEmailController::class)->name('verification.verify')
        ->middleware(['signed', 'throttle:6,1']);

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->name('verification.send')
        ->middleware('throttle:6,1');

    /**
     * Protected routed that only verified user can access.
     */
    Route::middleware('verified')->group(function () {
        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
        Route::put('password', [PasswordController::class, 'update'])->name('password.update');
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


        // TEST METHODS (delete them after tests ends)
        Route::controller(\App\Http\Controllers\TestController::class)->group(function () {
            Route::get('/create-encrypt-token', 'encryptTokenForm')->name('show-encrypt-token-form');
            Route::post('/create-encrypt-token', 'createEncryptToken')->name('create-token');

            Route::get('/encrypt/{token?}', 'showEncrypt')->name('show-encrypt');
            Route::post('/encrypt', 'encryptData')->middleware('encrypted')->name('encrypt');
            Route::get('/decrypt/{token?}/{encrypted?}', 'showDecrypt')->name('show-decrypt');
            // remove 'encrypted' middleware if you want to see how non-decrypted data will be displayed
            Route::post('/decrypt', 'decryptData')->middleware('encrypted')->name('decrypt');
        });
    });

});
