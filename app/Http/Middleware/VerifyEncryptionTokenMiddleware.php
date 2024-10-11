<?php

namespace App\Http\Middleware;

use App\Http\Requests\Encryption\MasterKeyRequest;
use App\Services\Encryption\MasterKey;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class VerifyEncryptionTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(MasterKeyRequest $request, Closure $next): Response
    {
        // MasterKeyRequest will verify token validity
        app(MasterKey::class, ['request' => $request])->decrypt();

        return $next($request);
    }
}
