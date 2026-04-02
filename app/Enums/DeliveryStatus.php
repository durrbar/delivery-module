<?php

declare(strict_types=1);

namespace Modules\Delivery\Enums;

enum DeliveryStatus: string
{
    case Pending = 'pending';
    case InTransit = 'in_transit';
    case Delivered = 'delivered';
    case Failed = 'failed';
}
