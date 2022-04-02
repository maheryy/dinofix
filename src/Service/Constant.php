<?php

namespace App\Service;

class Constant
{
    public const STATUS_DEFAULT = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_HOLD = 2;
    public const STATUS_VALIDATED = 3;
    public const STATUS_DONE = 4;
    public const STATUS_DELETED = -1;
    public const STATUS_INACTIVE = -2;
    public const STATUS_CANCELLED = -3;
}