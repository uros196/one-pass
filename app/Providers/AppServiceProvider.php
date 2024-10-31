<?php

namespace App\Providers;

use App\Services\Encryption\Basic\BasicSignature;
use App\Services\Encryption\Challenge\ChallengeSignature;
use App\Services\Encryption\Encrypter;
use App\Services\Encryption\EncryptionKey;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        // configure Challenge Encryptor
        $this->app->singleton('challenge-encrypter', function ($app) {
            return new Encrypter(
                key: EncryptionKey::makeFrom(request()->user()->encryptionKey->public_key)
                    ->setIterations(50000)
                    ->useAlgorithm('sha512')
                    ->setLength(32)
                    ->sign(ChallengeSignature::get())
                    ->generate(),

                cipher: $app->make('config')->get('app.cipher')
            );
        });

        // configure Basic Encryptor
        $this->app->singleton('basic-encrypter', function ($app) {
            return new Encrypter(
                key: EncryptionKey::makeFrom(request()->user()->encryptionKey->public_key)
                    ->setLength(32)
                    ->sign(BasicSignature::get())
                    ->generate(),

                cipher: $app->make('config')->get('app.cipher')
            );
        });

    }
}
