<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKataSolutionsTable extends Migration
{
   public function up()
   {
      Schema::create('kata_solutions', function (Blueprint $table) {
         $table->bigIncrements('id');
         $table->integer('task_id')->unsigned();
         $table->string('kata_id');
         $table->string('language');
         $table->text('code');
         $table->integer('code_len')->default(0);
         $table->string('code_hash');
         $table->text('comment')->nullable();
         $table->dateTime('solved_at');
         $table->boolean('can_show')->default(true);
         $table->timestamps();

         $table->unique(['task_id', 'kata_id', 'code_hash']);
      });
   }

   public function down()
   {
      Schema::dropIfExists('kata_solutions');
   }
}
