<?php

namespace App\Configs\ExpirableData;

use App\Contracts\SensitiveData\ExpirableNotificationContract;
use App\Models\BankCardData;
use App\Services\SensitiveData\Router;

class BankCardProvider implements ExpirableNotificationContract
{
    /**
     * BankCardProvider constructor.
     *
     * @param BankCardData $model
     */
    public function __construct(protected BankCardData $model) {}

    /**
     * Define an icon that will be displayed.
     *
     * @return string
     */
    public function icon(): string
    {
        // TODO: use card type to provide different icons
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
