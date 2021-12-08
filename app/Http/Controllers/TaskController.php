<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatusType;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\SetSolverAboutRequest;
use App\Http\Resources\TaskStatus;
use App\Jobs\CodewarsParseSolutionsJob;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TaskController extends Controller
{
   public function createTask(CreateTaskRequest $request): array
   {
      $task = Task::firstOrCreate([
         'edit_token' => hash('md5', Str::random()),
         'public_token' => hash('md5', Str::random()),
         'ip' => request()->ip(),
      ]);

      if ($request->has('cookies')) {
         $credentials = ['cookies' => $request->get('cookies')];
      } else {
         $credentials = [
            'login' => $request->get('login'),
            'password' => $request->get('password'),
         ];
      }

      CodewarsParseSolutionsJob::dispatch($task, $credentials);

      return ['token' => $task->edit_token];
   }

   /**
    * Update solutions from codewars
    */
   public function updateTask($editToken, CreateTaskRequest $request): array
   {
      if ($request->has('cookies')) {
         $credentials = ['cookies' => $request->get('cookies')];
      } else {
         $credentials = [
            'login' => $request->get('login'),
            'password' => $request->get('password'),
         ];
      }

      $shiftStatuses = $request->get('shiftStatuses', true);

      $task = Task::availableEditTask($editToken)->first();
      if (
         in_array($task->status, [
            TaskStatusType::Updating,
            TaskStatusType::Processing,
         ])
      ) {
         return ['error' => 'Task not done', 'error_code' => 1];
      }
      $task->status = TaskStatusType::Updating;
      $task->save();

      CodewarsParseSolutionsJob::dispatch($task, $credentials, $shiftStatuses);

      return ['status' => 'ok'];
   }

   public function generatePublicToken($editToken): array
   {
      $task = Task::availableEditTask($editToken)->first();
      $task->public_token = hash('md5', Str::random());
      $task->save();

      return ['status' => 'ok', 'token' => $task->public_token];
   }

   public function setSolverAbout(
      $editToken,
      SetSolverAboutRequest $request
   ): array {
      $task = Task::availableEditTask($editToken)->first();
      $task
         ->solver()
         ->updateOrCreate(
            ['id' => $task->id],
            ['about' => $request->get('about')]
         );

      return ['status' => 'ok'];
   }

   public function getTaskStatus($editToken): TaskStatus
   {
      $task = Task::availableEditTask($editToken)
         ->with('solver')
         ->with('logs')
         ->first();

      return new TaskStatus($task);
   }

   public function generateOfflineCopy()
   {
   }
}
