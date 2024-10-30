<?php

namespace App\Http\Controllers;

use App\Http\Requests\Encryption\ChallengeMasterKeyRequest;
use App\Models\Concerns\HasSensitiveData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestController extends Controller
{
    // this trait is using in the model
    use HasSensitiveData;

    /**
     * Show form for creating an 'Encrypt Token'.
     *
     * @return View
     */
    public function encryptTokenForm(): View
    {
        return view('test.token-form');
    }

    /**
     * Create 'Encrypt Token' and use it for unlock sensitive data.
     *
     * @param ChallengeMasterKeyRequest $request
     * @return RedirectResponse
     */
    public function createEncryptToken(ChallengeMasterKeyRequest $request): /*JsonResponse*/ RedirectResponse
    {
        // return response()->json(
        //     $request->user()->createToken($request)
        // );

        return redirect()->route('show-encrypt', [
            'token' => $request->user()->createToken($request)
        ]);
    }

    /**
     * Show an 'encrypt' form.
     *
     * @return View
     */
    public function showEncrypt(): View
    {
        return view('test.show-encrypt');
    }

    /**
     * Encrypt test data.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function encryptData(Request $request): /*JsonResponse*/ RedirectResponse
    {
        // return response()->json(
        //     $this->encrypt($request->get('data'))
        // );

        return redirect()->route('show-decrypt', [
            'token' => $request->get('encryption_token'),
            'encrypted' => $this->encrypt($request->get('data'))
        ]);
    }

    /**
     * Show a 'decrypt' form.
     *
     * @return View
     */
    public function showDecrypt(): View
    {
        return view('test.show-decrypt');
    }

    /**
     * Decrypt test data.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function decryptData(Request $request): JsonResponse
    {
        return response()->json(
            $this->decrypt($request->get('data'))
        );
    }
}
