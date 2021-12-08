<?php

namespace App\Http\Resources;

use App\Models\KataSolution;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin KataSolution
 */
class PublicSolution extends JsonResource
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
         'language' => $this->language,
         'code' => $this->code,
         'codeLength' => $this->code_len,
         'comment' => $this->comment,
         'solvedAt' => $this->solved_at->timestamp,
      ];
   }
}
