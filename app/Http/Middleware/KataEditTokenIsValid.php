<?php

namespace App\Http\Middleware;

use App\Enums\TaskStatusType;
use App\Models\Task;
use Closure;
use Illuminate\Http\Request;

class KataEditTokenIsValid
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

      return tokenMiddlewareHelper($request, $next, $task, [
         TaskStatusType::Done,
         TaskStatusType::Updating,
      ]);
   }
}
