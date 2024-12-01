<?php

namespace App\DataRegistrars;

use App\Contracts\Models\DocumentDataContract;
use App\Contracts\SensitiveData\Resolvers\DataRegistrar;
use App\Models\SensitiveDataConnection;
use App\Services\SensitiveData\Resolvers\InteractWithRequest;
use App\Services\SensitiveData\Router;
use App\Services\SensitiveData\Schedulers\IsAboutToExpire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DocumentDataRegistrar implements DataRegistrar
{
    use InteractWithRequest;

    /**
     * Store Document data into DB.
     *
     * @param FormRequest $request
     * @return Model
     */
    public function store(FormRequest $request): Model
    {
        $model    = $this->getDocModel();
        $relation = $model->relationName();
        $class    = get_class($model);

        $data = $class::create([
            ...$request->validated(),
            'type' => $class::documentType()
        ]);
        auth()->user()->{$relation}()->attach($data);

        return $data;
    }

    /**
     * Update existing Document data.
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
     * Get the list of the user Document data.
     *
     * @return Collection|LengthAwarePaginator
     */
    public function list(): Collection|LengthAwarePaginator
    {
        return auth()->user()->{$this->getDocModel()->relationName()}
            // load relation that handles expiration date
            ->load('dataConnection.dataExpirationDate');
    }

    /**
     * Get the current document empty model.
     * We need this model for getting data that'd specific for the current doc type.
     *
     * @return DocumentDataContract
     */
    protected function getDocModel(): DocumentDataContract
    {
        $type = $this->getType();
        return new (Router::getConfigByType($type)['model']);
    }
}
