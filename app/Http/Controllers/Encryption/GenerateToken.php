<?php

namespace App\Http\Controllers\Encryption;

use App\Http\Controllers\Controller;
use App\Http\Requests\Encryption\ChallengeMasterKeyRequest;
use Illuminate\Http\JsonResponse;

class GenerateToken extends Controller
{
    /**
     * Generate encryption token
     *
     * @param ChallengeMasterKeyRequest $request
     * @return JsonResponse
     */
    public function __invoke(ChallengeMasterKeyRequest $request): JsonResponse
    {
        return $this->sendResponse([
            'token' => $request->user()->createToken($request)
        ]);
    }
}
