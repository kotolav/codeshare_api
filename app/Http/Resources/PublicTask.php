<?php

namespace App\Http\Resources;

use Barryvdh\Reflection\DocBlock\Type\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicTask extends JsonResource
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
         'solver' => new Solver($this->solver),
         'katas' => PublicKatas::collection($this->katas),
      ];
   }
}
