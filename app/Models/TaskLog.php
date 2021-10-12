<?php

namespace App\Models;

use App\Enums\TaskLogType;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TaskLog
 *
 * @property int $id
 * @property int $task_id
 * @property string $message
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TaskLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskLog whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskLog whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TaskLog extends Model
{
   protected $guarded = ['id'];
   protected $casts = [
      'status' => TaskLogType::class,
   ];
}
