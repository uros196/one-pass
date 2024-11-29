<?php

use App\Http\Controllers\Auth\ActivityCheckController;
use App\Http\Controllers\DesignStaticController;
use App\Http\Controllers\Encryption\GenerateToken;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SensitiveDataController;
use App\Services\SensitiveData\Router as DataRouter;
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

Route::get('/design-static/{page?}', DesignStaticController::class)->name('design-static');

Route::middleware(['auth', 'verified', 'password.confirm'])->group(function () {
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::controller(SensitiveDataController::class)
        ->prefix('sensitive-data/{type}')
        ->whereIn('type', DataRouter::getAvailableTypes())
        ->group(function () {
            Route::get('/', 'index')->name('sensitive-data.index');
            Route::get('/create', 'create')->name('sensitive-data.create');
            Route::post('/', 'store')->middleware('encrypted')->name('sensitive-data.store');
            Route::get('/{id}', 'show')->whereUuid('id')->name('sensitive-data.show');
            Route::get('/{id}/edit', 'edit')->whereUuid('id')->middleware('encrypted')->name('sensitive-data.edit');
            Route::patch('/{id}', 'update')->whereUuid('id')->middleware('encrypted')->name('sensitive-data.update');
            Route::delete('/{id}', 'destroy')->whereUuid('id')->name('sensitive-data.destroy');
        });

    Route::post('/generate-encryption-token', GenerateToken::class)->name('generate-encryption-token');

});

Route::get('/activity/check', ActivityCheckController::class)->name('activity.check');

require __DIR__.'/auth.php';
