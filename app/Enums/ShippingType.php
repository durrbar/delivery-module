<?php

declare(strict_types=1);

namespace Modules\Delivery\Enums;

enum ShippingType: string
{
    case Fixed = 'fixed';
    case Percentage = 'percentage';
    case Free = 'free_shipping';
}
