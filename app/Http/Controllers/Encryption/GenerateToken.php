<?php

namespace App\Http\Controllers\Encryption;

use App\Http\Controllers\Controller;
use App\Http\Requests\Encryption\MasterKeyRequest;
use Illuminate\Http\JsonResponse;

class GenerateToken extends Controller
{
    /**
     * Generate encryption token
     *
     * @param MasterKeyRequest $request
     * @return JsonResponse
     */
    public function __invoke(MasterKeyRequest $request): JsonResponse
    {
        return $this->sendResponse([
            'token' => $request->user()->createToken($request)
        ]);
    }
}
