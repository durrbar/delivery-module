<?php

namespace Modules\Delivery\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\Delivery\Models\DeliveryTime;

class DeliveryTimeRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return DeliveryTime::class;
    }
}
