<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublicKatas;
use App\Http\Resources\PublicTask;
use App\Models\Task;
use stdClass;

class PublicKataController extends Controller
{
   public function info($publicToken): PublicTask
   {
      $task = Task::with('solver')
         ->availablePublicTask($publicToken)
         ->first();
      $collection = $task
         ->katas(true)
         ->solutionsForTask($task->id, true)
         ->get();

      $publicTask = new stdClass();
      $publicTask->solver = $task->solver;
      $publicTask->katas = $collection;

      return new PublicTask($publicTask);
   }
}
