<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tag
 *
 * @property int $id
 * @property string $tag
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereTag($value)
 * @mixin \Eloquent
 */
class Tag extends Model
{
   public $guarded = ['id'];
   protected $hidden = ['pivot'];
   public $timestamps = false;
}
