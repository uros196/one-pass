<?php

namespace App\Models\Concerns;

use App\Models\User;
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
        return $this->morphedByMany(User::class, 'connectable', 'sensitive_data_connections');
    }
}
