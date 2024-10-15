<?php

namespace App\Http\Middleware;

use App\Services\Encryption\MasterKey;
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
        // 'MasterKeyRequest' will verify token validity
        app(MasterKey::class)->decrypt();

        return $next($request);
    }
}
