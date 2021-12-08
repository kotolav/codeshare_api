<?php

namespace App\Http\Resources;

use App\Models\Solver as SolverModel;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin SolverModel
 */
class Solver extends JsonResource
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
         'nick' => $this->nick,
         'about' => $this->about,
      ];
   }
}
