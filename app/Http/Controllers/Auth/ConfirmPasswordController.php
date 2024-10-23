<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Password\ConfirmPasswordRequest;
use App\Services\Auth\PasswordService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ConfirmPasswordController extends Controller
{
    /**
     * Show the confirmation password view.
     */
    public function show(Request $request): Response|RedirectResponse
    {
        return app(PasswordService::class)->shouldConfirm($request)
            ? Inertia::render('Auth/ConfirmPassword')
            : $this->intendedRedirect();
    }

    /**
     * Confirm the user's password.
     */
    public function store(ConfirmPasswordRequest $request): RedirectResponse
    {
        return $this->intendedRedirect();
    }

    /**
     * Create intended redirection.
     *
     * @return RedirectResponse
     */
    protected function intendedRedirect(): RedirectResponse
    {
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
