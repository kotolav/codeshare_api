<?php

namespace App\Http\Controllers;

use App\Http\Resources\EditKatas;
use App\Models\KataSolution;
use App\Models\Task;

class EditKataController extends Controller
{
   public function katas($editToken)
   {
      $task = Task::availableEditTask($editToken)->first();
      $collection = $task
         ->katas(false)
         ->solutionsForTask($task->id)
         ->get();

      return EditKatas::collection($collection);
   }

   public function hideAllSolutions($editToken, $kataId): array
   {
      $task = Task::availableEditTask($editToken)->first();
      KataSolution::whereTaskId($task->id)
         ->whereKataId($kataId)
         ->update(['can_show' => false]);

      return ['status' => 'ok'];
   }

   public function toggleKataVisibility($editToken, $kataId, $solutionId): array
   {
      $task = Task::availableEditTask($editToken)->first();
      $solution = KataSolution::whereTaskId($task->id)
         ->whereKataId($kataId)
         ->whereId($solutionId)
         ->first();

      $solution->can_show = request()->get('isShowing');
      $solution->save();

      return ['status' => 'ok'];
   }

   public function setComment($editToken, $kataId, $solutionId): array
   {
      $task = Task::availableEditTask($editToken)->first();
      $solution = KataSolution::whereTaskId($task->id)
         ->whereKataId($kataId)
         ->whereId($solutionId)
         ->first();

      $solution->comment = request()->get('comment', null);
      $solution->save();

      return ['status' => 'ok'];
   }
}
