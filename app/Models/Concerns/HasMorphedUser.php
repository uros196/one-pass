<?php

namespace App\Models\Concerns;

use App\Models\SensitiveDataConnection;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasMorphedUser
{
    /**
     * Get the morphed related User.
     *
     * @return MorphToMany
     */
    public function user(): MorphToMany
    {
        return $this->morphToMany(User::class, 'connectable', 'sensitive_data_connections');
    }

    /**
     * Make the relation to the 'connections' table (where all connections are stored).
     *
     * @return MorphOne
     */
    public function dataConnection(): MorphOne
    {
        return $this->morphOne(SensitiveDataConnection::class, 'connectable');
    }
}
