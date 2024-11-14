<?php

namespace App\Models\Traits;

use App\Models\LoginData;
use App\Models\NoteData;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait SensitiveDataConnections
{
    /**
     * Get related Logins data related to the User.
     *
     * @return MorphToMany
     */
    public function loginData(): MorphToMany
    {
        return $this->morphToMany(LoginData::class, 'connectable', 'sensitive_data_connections');
    }

    /**
     * Get related Notes data related to the User.
     *
     * @return MorphToMany
     */
    public function noteData(): MorphToMany
    {
        return $this->morphToMany(NoteData::class, 'connectable', 'sensitive_data_connections');
    }
}
