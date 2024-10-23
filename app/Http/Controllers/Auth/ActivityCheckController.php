<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\PasswordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;

class ActivityCheckController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     *
     * @return \Illuminate\Routing\Controllers\Middleware[]
     */
    public static function middleware(): array
    {
        return [
            'throttle:10,1'
        ];
    }

    /**
     * Check user last password confirmation, and request it if needed.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        // if the session expired
        if (!Auth::guard()->check()) {
            return $this->intended(route('login'));
        }

        // if the use confirmed password long ago (needs new confirmation)
        if (app(PasswordService::class)->shouldConfirm($request)) {
            return $this->intended(route('password.confirm'));
        }

        // return an empty response with status code 200
        return response()->json();
    }

    /**
     * Remember 'intended URL' and create JSON response.
     *
     * @param string $redirect
     * @return JsonResponse
     */
    protected function intended(string $redirect): JsonResponse
    {
        // use 'guest' function to remember intended URL
        // so it can be properly redirected after confining a password (or login)
        redirect()->guest($redirect);

        return response()->json(compact('redirect'), 401);
    }
}
