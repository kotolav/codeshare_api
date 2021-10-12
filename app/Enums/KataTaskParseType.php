<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static NotProcessed()
 * @method static static Processed()
 * @method static static Error()
 */
final class KataTaskParseType extends Enum
{
   const NotProcessed = 0;
   const Processed = 1;
   const Error = 2;
}
