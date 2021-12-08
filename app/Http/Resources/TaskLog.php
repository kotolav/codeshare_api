<?php

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @mixin \App\Models\TaskLog
 */
class TaskLog extends JsonResource
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
         'type' => $this->type,
         'time' => $this->created_at->timestamp,
      ];
   }
}
