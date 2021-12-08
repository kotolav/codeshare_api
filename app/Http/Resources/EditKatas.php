<?php

namespace App\Http\Resources;

use App\Models\Kata;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Kata
 */
class EditKatas extends JsonResource
{
   /**
    * Transform the resource into an array.
    *
    * @param Request $request
    *
    * @return array
    */
   public function toArray($request): array
   {
      return [
         'id' => $this->id,
         'name' => $this->name,
         'description' => $this->description,
         'rank' => $this->rank_text,
         'tags' => collect($this->tags)->pluck('tag'),
         'solutions' => EditSolution::collection($this->solutions),
      ];
   }
}
