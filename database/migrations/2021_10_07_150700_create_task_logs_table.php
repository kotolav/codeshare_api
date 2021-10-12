<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskLogsTable extends Migration
{
   public function up()
   {
      Schema::create('task_logs', function (Blueprint $table) {
         $table->bigIncrements('id');
         $table->integer('task_id');
         $table->string('message');
         $table->string('type');
         $table->timestamps();
      });
   }

   public function down()
   {
      Schema::dropIfExists('task_logs');
   }
}
