<?php

namespace App\Models\Traits;

use App\Models\LoginData;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait SensitiveDataConnections
{
    /**
     * Get related Login data related to the User.
     *
     * @return MorphToMany
     */
    public function loginData(): MorphToMany
    {
        return $this->morphToMany(LoginData::class, 'connectable', 'sensitive_data_connections');
    }

}
