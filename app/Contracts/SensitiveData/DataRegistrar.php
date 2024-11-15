<?php

namespace App\Contracts\SensitiveData;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface DataRegistrar
{
    /**
     * Store sensitive data into DB.
     *
     * @param FormRequest $request
     * @return void
     */
    public function store(FormRequest $request): void;

    /**
     * Update existing sensitive data.
     *
     * @param FormRequest $request
     * @param Model $model
     *
     * @return void
     */
    public function update(FormRequest $request, Model $model): void;

    /**
     * Get the list of the data.
     *
     * @return Collection|LengthAwarePaginator
     */
    public function list(): Collection|LengthAwarePaginator;
}
