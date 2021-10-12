<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Added()
 * @method static static Processing()
 * @method static static Done()
 * @method static static Fail()
 */
final class TaskStatusType extends Enum
{
   const Added = 0;
   const Processing = 1;
   const Done = 2;
   const Fail = 3;
}
