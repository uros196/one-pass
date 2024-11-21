<?php

namespace App\Http\Controllers\Encryption;

use App\Http\Controllers\Controller;
use App\Http\Requests\Encryption\ChallengeSignatureRequest;
use Illuminate\Support\Facades\Redirect;

class GenerateToken extends Controller
{
    /**
     * Generate encryption token
     *
     * @param ChallengeSignatureRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(ChallengeSignatureRequest $request)
    {
        return Redirect::back()->with('flash', [
            'token' => $request->user()->createToken($request)
        ]);
    }
}
