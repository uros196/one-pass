<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LockoutScreenController extends Controller
{
    /**
     * Lockout user's screen.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function __invoke(Request $request): RedirectResponse
    {
        // reset last password confirmation
        $request->session()->put('auth.password_confirmed_at', 0);

        // remember the previous URL (before lockout) so we can return
        // the user where it has been
        redirect()->setIntendedUrl(url()->previous());

        return redirect()->route('password.confirm');
    }
}
