<?php

namespace App\DataRegistrars;

use App\Contracts\SensitiveData\DataRegistrar;
use App\Models\LoginData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class LoginDataRegistrar implements DataRegistrar
{
    /**
     * Store Login data into DB.
     *
     * @param FormRequest $request
     * @return Model
     */
    public function store(FormRequest $request): Model
    {
        $loginData = LoginData::create($request->validated());
        auth()->user()->loginData()->attach($loginData);

        return $loginData;
    }

    /**
     * Update existing Login data.
     *
     * @param FormRequest $request
     * @param Model $model
     *
     * @return void
     */
    public function update(FormRequest $request, Model $model): void
    {
        $model->update($request->validated());
    }

    /**
     * Get the list of the user Login data.
     *
     * @return Collection|LengthAwarePaginator
     */
    public function list(): Collection|LengthAwarePaginator
    {
        return auth()->user()->loginData;
    }

}
