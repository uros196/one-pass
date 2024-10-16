<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UnlockAccountAuthorizationRequest;
use App\Http\Requests\Auth\UnlockAccountRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UnlockAccountController extends Controller
{
    /**
     * Display unlocking account prompt (show to password confirmation field).
     *
     * @param UnlockAccountAuthorizationRequest $request
     * @return RedirectResponse|Response
     */
    public function show(UnlockAccountAuthorizationRequest $request): RedirectResponse|Response
    {
        return !User::isAccountLocked($request->decryptEmail())
            ? redirect()->intended(route('dashboard', absolute: false))
            : Inertia::render('Auth/UnlockAccount', ['hash' => $request->hash]);
    }

    /**
     * Unlock the account.
     *
     * @param UnlockAccountRequest $request
     * @return RedirectResponse
     */
    public function store(UnlockAccountRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        $request->user()->unlockAccount();

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
