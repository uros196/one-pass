<?php

namespace App\Configs\ExpirableData;

use App\Contracts\SensitiveData\ExpirableNotificationContract;
use App\Models\Concerns\HasDocumentData;
use App\Models\IdCardDocument;
use App\Services\SensitiveData\Router;
use Illuminate\Database\Eloquent\Model;

class DocumentProvider implements ExpirableNotificationContract
{
    /**
     * DocumentProvider constructor.
     *
     * @param IdCardDocument $model - IdCardDocument is a one of the many models that'd operate with the same table
     * @throws \Exception
     */
    public function __construct(protected Model $model)
    {
        if (!in_array(HasDocumentData::class, class_uses_recursive($this->model))) {
            throw new \Exception('Class '. get_class($this->model) .' must use '. HasDocumentData::class .' trait');
        }
    }

    /**
     * Define an icon that will be displayed.
     *
     * @return string
     */
    public function icon(): string
    {
        // TODO: use doc type to provide different icons
        return '';
    }

    /**
     * Define a name that will be displayed.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->model->name;
    }

    /**
     * Define the edit/show link that will be routed to.
     *
     * @return string
     */
    public function link(): string
    {
        return route('sensitive-data.show', [
            'type' => Router::getTypeByModel($this->model),
            'id' => $this->model->id,
        ]);
    }
}
