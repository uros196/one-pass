<?php

namespace App\DataRegistrars;

use App\Contracts\SensitiveData\DataRegistrar;
use App\Models\NoteData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class NoteDataRegistrar implements DataRegistrar
{
    /**
     * Store Note data into DB.
     *
     * @param FormRequest $request
     * @return Model
     */
    public function store(FormRequest $request): Model
    {
        $noteData = NoteData::create($request->validated());
        auth()->user()->noteData()->attach($noteData);

        return $noteData;
    }

    /**
     * Update existing Note data.
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
     * Get the list of the user Note data.
     *
     * @return Collection|LengthAwarePaginator
     */
    public function list(): Collection|LengthAwarePaginator
    {
        return auth()->user()->noteData;
    }

}
