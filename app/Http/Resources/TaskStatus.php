<?php

namespace App\Http\Resources;

use App\Models\KataSolution;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Task
 */
class TaskStatus extends JsonResource
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
         'editToken' => $this->edit_token,
         'publicToken' => $this->public_token,
         'status' => $this->status,
         'solver' => new Solver($this->whenLoaded('solver')),
         'logs' => TaskLog::collection($this->whenLoaded('logs')),
      ];
   }
}
