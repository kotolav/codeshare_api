<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Solver
 *
 * @property int $id
 * @property string|null $nick
 * @property string|null $about
 * @method static \Illuminate\Database\Eloquent\Builder|Solver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Solver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Solver query()
 * @method static \Illuminate\Database\Eloquent\Builder|Solver whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Solver whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Solver whereNick($value)
 * @mixin \Eloquent
 */
class Solver extends Model
{
   protected $fillable = ['nick', 'about'];
   public $timestamps = false;
}
