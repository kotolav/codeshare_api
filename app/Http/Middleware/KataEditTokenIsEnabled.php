<?php

namespace App\Http\Middleware;

use App\Enums\TaskStatusType;
use App\Models\Task;
use Closure;
use Illuminate\Http\Request;

class KataEditTokenIsEnabled
{
   /**
    * Handle an incoming request.
    *
    * @param Request $request
    * @param Closure $next
    * @return mixed
    */
   public function handle(Request $request, Closure $next)
   {
      $editToken = $request->route('editToken', '');
      $task = Task::firstWhere('edit_token', $editToken);

      if ($task) {
         if ($task->enabled) {
            return $next($request);
         } else {
            return response(
               ['error' => 'Access token is disabled', 'error_code' => 2],
               403
            );
         }
      } else {
         return response(
            ['error' => 'Access token is invalid', 'error_code' => 3],
            403
         );
      }
   }
}
