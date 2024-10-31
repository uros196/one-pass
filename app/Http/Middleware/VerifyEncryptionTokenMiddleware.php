<?php

namespace App\Http\Middleware;

use App\Services\Encryption\Challenge\ChallengeSignature;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyEncryptionTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 'ChallengeMasterKeyRequest' will verify token validity
        app(ChallengeSignature::class)->decrypt();

        // regenerate encryption token foreign key after successful validation
        $request->user()->regenerateTokenForeignKey();

        return $next($request);
    }
}
