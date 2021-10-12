<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Kata
 *
 * @property string $id
 * @property string $name
 * @property string|null $rank
 * @property string|null $category
 * @property string|null $description
 * @property int|null $total_attempts
 * @property int|null $total_completed
 * @property int $process_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder|Kata newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kata newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kata query()
 * @method static \Illuminate\Database\Eloquent\Builder|Kata whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kata whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kata whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kata whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kata whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kata whereProcessStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kata whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kata whereTotalAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kata whereTotalCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kata whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Kata extends Model
{
   protected $guarded = [];
   public $incrementing = false;

   public function tags(): BelongsToMany
   {
      return $this->belongsToMany(Tag::class, 'kata_tag', 'kata_id', 'tag_id');
   }
}
