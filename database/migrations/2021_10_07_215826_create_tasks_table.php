<?php

use App\Enums\TaskStatusType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
   public function up()
   {
      Schema::create('tasks', function (Blueprint $table) {
         $table->bigIncrements('id');
         $table->string('edit_token')->unique();
         $table->string('public_token')->unique();
         $table->string('status')->default(TaskStatusType::Added);
         $table->boolean('enabled')->default(true);
         $table->ipAddress('ip');
         $table->timestamps();
      });
   }

   public function down()
   {
      Schema::dropIfExists('tasks');
   }
}
