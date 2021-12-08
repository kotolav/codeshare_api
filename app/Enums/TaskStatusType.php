<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Added()
 * @method static static Processing()
 * @method static static Updating()
 * @method static static Done()
 * @method static static Fail()
 */
final class TaskStatusType extends Enum
{
   const Added = 'added';
   const Processing = 'processing';
   const Updating = 'updating';
   const Done = 'done';
   const Fail = 'fail';
}
