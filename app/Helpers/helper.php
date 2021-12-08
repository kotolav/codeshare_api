<?php

use Illuminate\Http\Request;
use App\Enums\TaskStatusType;
use App\Models\Task;

/**
 * @param Request $request
 * @param Closure $next
 * @param Task | null  $task
 * @param $validStatuses
 *
 * @return mixed
 */
function tokenMiddlewareHelper(
   Request $request,
   Closure $next,
   ?Task $task,
   $validStatuses
) {
   if ($task) {
      if ($task->enabled && in_array($task->status->value, $validStatuses)) {
         return $next($request);
      } else {
         if ($task->status->value !== TaskStatusType::Done) {
            return response(
               ['error' => 'Task not done', 'error_code' => 1],
               200
            );
         } else {
            return response(
               ['error' => 'Access token is disabled', 'error_code' => 2],
               403
            );
         }
      }
   } else {
      return response(
         ['error' => 'Access token is invalid', 'error_code' => 3],
         403
      );
   }
}
