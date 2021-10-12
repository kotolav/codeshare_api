<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\KataSolution
 *
 * @property int $id
 * @property int $task_id
 * @property string $kata_id
 * @property string $language
 * @property string $code
 * @property int $code_len
 * @property string $code_hash
 * @property string|null $comment
 * @property string $solved_at
 * @property int $can_show
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|KataSolution newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KataSolution newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KataSolution query()
 * @method static \Illuminate\Database\Eloquent\Builder|KataSolution whereCanShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KataSolution whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KataSolution whereCodeHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KataSolution whereCodeLen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KataSolution whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KataSolution whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KataSolution whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KataSolution whereKataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KataSolution whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KataSolution whereSolvedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KataSolution whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KataSolution whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class KataSolution extends Model
{
   protected $guarded = ['id'];
   protected $casts = [
      //      'solvedAt' => Date,
   ];
   public function kata()
   {
      $this->belongsTo(Kata::class);
   }
}
