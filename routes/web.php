<?php

use App\Http\Controllers\Auth\ActivityCheckController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified', 'password.confirm'])->group(function () {
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



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

Route::get('/activity/check', ActivityCheckController::class)->name('activity.check');

require __DIR__.'/auth.php';
