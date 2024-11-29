<?php

namespace App\Contracts\SensitiveData;

use Illuminate\Database\Eloquent\Model;

interface DataModel
{
    /**
     * Get the bound Model.
     *
     * @return Model
     */
    public function get(): Model;
}
