<?php

namespace App\Models\Traits;

use App\Models\BankCardData;
use App\Models\LoginData;
use App\Models\NoteData;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait SensitiveDataConnections
{
    /**
     * Get related Logins data associated with the User.
     *
     * @return MorphToMany
     */
    public function loginData(): MorphToMany
    {
        return $this->morphedByMany(LoginData::class, 'connectable', 'sensitive_data_connections');
    }

    /**
     * Get related Notes data associated with the User.
     *
     * @return MorphToMany
     */
    public function noteData(): MorphToMany
    {
        return $this->morphedByMany(NoteData::class, 'connectable', 'sensitive_data_connections');
    }

    /**
     * Get related Bank Card data associated with the User.
     *
     * @return MorphToMany
     */
    public function bankCardData(): MorphToMany
    {
        return $this->morphedByMany(BankCardData::class, 'connectable', 'sensitive_data_connections');
    }
}
