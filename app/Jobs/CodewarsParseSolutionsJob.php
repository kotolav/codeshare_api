<?php

namespace App\Jobs;

use App\Classes\Codewars\CodewarsCrawler;
use App\Classes\Codewars\Exception\AuthFailException;
use App\Classes\Codewars\Exception\ParseException;
use App\Classes\Codewars\HtmlParser\Exception\ParseResponseException;
use App\Enums\KataTaskParseType;
use App\Enums\TaskLogType;
use App\Enums\TaskStatusType;
use App\Models\Kata;
use App\Models\KataSolution;
use App\Models\Tag;
use App\Models\Task;
use App\Models\TaskLog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Arr;
use Throwable;

class CodewarsParseSolutionsJob implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   public int $timeout = 300;

   private Task $task;
   private array $credentials;
   private CodewarsCrawler $codewarsCrawler;
   private bool $isUpdating;

   public function __construct(
      Task $task,
      array $credentials,
      $isUpdating = false
   ) {
      $this->task = $task;
      $this->credentials = $credentials;
      $this->isUpdating = $isUpdating;
   }

   public function handle(CodewarsCrawler $codewarsCrawler)
   {
      $this->codewarsCrawler = $codewarsCrawler;
      $this->changeTaskStatus(TaskStatusType::Processing);
      $this->addTaskLog('The parsing task is running');

      try {
         $solutions = $this->parseSolutions();
         $this->saveParsedSolutions($solutions);
         $this->saveOwnerLogin();

         $kataIds = $this->getKataIdsWithoutDescription($solutions);
         $descriptions = $this->codewarsCrawler->getKataDescriptions($kataIds);
         $this->updateKatasDescription($descriptions);

         $this->changeTaskStatus(TaskStatusType::Done);
      } catch (AuthFailException | ParseException | ParseResponseException | Throwable $e) {
         $this->changeTaskStatus(TaskStatusType::Fail);
         if ($e instanceof AuthFailException) {
            $this->addTaskLog(
               'Parsing task aborted. Codewars auth error: ' . $e->getMessage(),
               TaskLogType::Error
            );
         } else {
            $this->addTaskLog(
               'Parsing task aborted. Parse solutions error: ' .
                  $e->getMessage(),
               TaskLogType::Error
            );
         }
      } finally {
         $this->addTaskLog('Parsing solutions has finished');
      }
   }

   /**
    * @throws ParseResponseException
    * @throws AuthFailException
    * @throws ParseException
    */
   private function parseSolutions(): array
   {
      if (Arr::exists($this->credentials, 'cookies')) {
         return $this->codewarsCrawler->getSolutionsWithCookies(
            $this->credentials['cookies']
         );
      } else {
         return $this->codewarsCrawler->getSolutionsWithLoginPassword(
            $this->credentials['login'],
            $this->credentials['password']
         );
      }
   }

   private function saveParsedSolutions($kataSolutions)
   {
      $katas = [];
      $solutions = [];
      foreach ($kataSolutions as $kata) {
         $katas[] = [
            'id' => $kata['id'],
            'name' => $kata['name'],
            'rank' => $kata['rank'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
         ];

         foreach ($kata['solutions'] as $solution) {
            $solutions[] = [
               'task_id' => $this->task->id,
               'kata_id' => $kata['id'],
               'language' => $solution['language'],
               'code' => $solution['code'],
               'code_len' => strlen($solution['code']),
               'code_hash' => hash('sha256', $solution['code']),
               'solved_at' => Carbon::createFromTimestamp($solution['date']),
               'created_at' => date('Y-m-d H:i:s'),
               'updated_at' => date('Y-m-d H:i:s'),
            ];
         }
      }

      if ($katas) {
         Kata::insertOrIgnore($katas);
         KataSolution::insertOrIgnore($solutions);
      }
   }

   private function addTaskLog($message, $messageType = TaskLogType::Info)
   {
      TaskLog::create([
         'task_id' => $this->task->id,
         'message' => $message,
         'type' => $messageType,
      ]);
   }

   private function changeTaskStatus($status): void
   {
      // Rewrite status. If task already parsed, and now it's just updating
      // So shift statuses for Processing to Updating, for Fail to Done
      if ($this->isUpdating) {
         if ($status === TaskStatusType::Processing) {
            $status = TaskStatusType::Updating;
         } elseif ($status === TaskStatusType::Fail) {
            $status = TaskStatusType::Done;
         }
      }
      $this->task->update(['status' => $status]);
   }

   private function getKataIdsWithoutDescription(array $solutions): array
   {
      $ids = array_column($solutions, 'id');
      return Kata::whereIn('id', $ids)
         ->where('process_status', KataTaskParseType::NotProcessed)
         ->pluck('id')
         ->all();
   }

   private function updateKatasDescription($katasDescriptions)
   {
      foreach ($katasDescriptions as $katasDescription) {
         $newDescriptions = [
            'name' => $katasDescription['name'],
            'category' => $katasDescription['category'],
            'description' => $katasDescription['description'],
            'rank' => $katasDescription['rank'],
            'total_attempts' => $katasDescription['totalAttempts'],
            'total_completed' => $katasDescription['totalCompleted'],
            'process_status' => KataTaskParseType::Processed,
         ];
         $kata = Kata::updateOrCreate(
            ['id' => $katasDescription['id']],
            $newDescriptions
         );
         foreach ($katasDescription['tags'] as $tag) {
            $tagModel = Tag::firstOrCreate(['tag' => $tag]);
            $kata->tags()->attach($tagModel);
         }
      }
   }

   private function saveOwnerLogin()
   {
      $ownerLogin = $this->codewarsCrawler->getLogin();
      $this->task
         ->solver()
         ->updateOrCreate(['id' => $this->task->id], ['nick' => $ownerLogin]);
   }
}
