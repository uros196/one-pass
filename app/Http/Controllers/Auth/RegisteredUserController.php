<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Services\Auth\RegistrationService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param RegistrationRequest $request
     * @param RegistrationService $service
     *
     * @return RedirectResponse
     */
    public function store(RegistrationRequest $request, RegistrationService $service): RedirectResponse
    {
        $service($request);

        return redirect(route('dashboard', absolute: false));
    }
}
