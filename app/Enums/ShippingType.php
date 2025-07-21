<?php

namespace Modules\Delivery\Enums;

use BenSampo\Enum\Enum;

/**
 * Class RoleType
 */
final class ShippingType extends Enum
{
    public const FIXED = 'fixed';

    public const PERCENTAGE = 'percentage';

    public const FREE = 'free_shipping';
    // public const DEFAULT = 'fixed';
}
