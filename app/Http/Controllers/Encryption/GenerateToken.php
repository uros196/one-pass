<?php

namespace App\Http\Controllers\Encryption;

use App\Http\Controllers\Controller;
use App\Http\Requests\Encryption\ChallengeSignatureRequest;
use Illuminate\Http\JsonResponse;

class GenerateToken extends Controller
{
    /**
     * Generate encryption token
     *
     * @param ChallengeSignatureRequest $request
     * @return JsonResponse
     */
    public function __invoke(ChallengeSignatureRequest $request): JsonResponse
    {
        return $this->sendResponse([
            'token' => $request->user()->createToken($request)
        ]);
    }
}
