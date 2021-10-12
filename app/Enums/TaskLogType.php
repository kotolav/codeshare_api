<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Info()
 * @method static static Error()
 */
final class TaskLogType extends Enum
{
   const Info = 0;
   const Error = 1;
}
