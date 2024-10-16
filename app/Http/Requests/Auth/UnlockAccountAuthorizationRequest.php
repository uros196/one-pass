<?php

namespace App\Http\Requests\Auth;

use App\Traits\AuthorizeUnlockRequest;
use Illuminate\Foundation\Http\FormRequest;

class UnlockAccountAuthorizationRequest extends FormRequest
{
    use AuthorizeUnlockRequest;
}
