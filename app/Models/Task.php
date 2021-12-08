<?php

namespace App\Models;

use App\Enums\TaskStatusType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Task
 *
 * @property int $id
 * @property string $edit_token
 * @property string $public_token
 * @property TaskStatusType $status
 * @property int $enabled
 * @property string $ip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TaskLog[] $logs
 * @property-read int|null $logs_count
 * @property-read \App\Models\Solver|null $solver
 * @method static \Illuminate\Database\Eloquent\Builder|Task availableEditTask($token)
 * @method static \Illuminate\Database\Eloquent\Builder|Task availablePublicTask($token)
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereEditToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task wherePublicToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Task extends Model
{
   protected $guarded = ['id'];
   protected $casts = [
      'status' => TaskStatusType::class,
   ];

   public function logs(): HasMany
   {
      return $this->hasMany(TaskLog::class);
   }

   public function solver(): HasOne
   {
      return $this->hasOne(Solver::class, 'id', 'id');
   }

   public function scopeAvailableEditTask($query, $token)
   {
      return $query->where('edit_token', $token)->where('enabled', true);
   }

   public function scopeAvailablePublicTask($query, $token)
   {
      return $query->where('public_token', $token)->where('enabled', true);
   }

   public function katas($publicOnly = true)
   {
      $query = $this->hasManyThrough(
         Kata::class,
         KataSolution::class,
         'task_id',
         'id',
         'id',
         'kata_id'
      );
      if ($publicOnly) {
         $query->where('can_show', true);
      }

      return $query->distinct();
   }
}
