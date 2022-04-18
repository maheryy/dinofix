<?php

namespace App\Service;

class Constant
{
    public const STATUS_DEFAULT = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_PAUSED = 2;
    public const STATUS_VALIDATED = 3;
    public const STATUS_DONE = 4;
    public const STATUS_DELETED = -1;
    public const STATUS_INACTIVE = -2;
    public const STATUS_CANCELLED = -3;


    public const ACTION_READ = 'read';
    public const ACTION_ADD = 'add';
    public const ACTION_EDIT = 'edit';
    public const ACTION_DELETE = 'delete';
    public const ACTION_CONTINUE = 'continue';
    public const ACTION_PAUSE = 'pause';
    public const ACTION_FINISH = 'end';
    public const ACTION_CANCEL = 'cancel';
}