<?php

namespace App\Traits;

trait AuthorizeUnlockRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        try {
            $this->decryptEmail();
            return true;
        }
        // if exception occurred, it means that encrypted string is not right
        catch (\Exception|\Throwable $exception) {
            return false;
        }
    }

    /**
     * Decrypt email so we can use it later.
     *
     * @return string
     */
    public function decryptEmail(): string
    {
        return decrypt($this->hash);
    }
}
