<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
   public function up()
   {
      Schema::create('tags', function (Blueprint $table) {
         $table->bigIncrements('id');
         $table->string('tag')->unique();
      });

      Schema::create('kata_tag', function (Blueprint $table) {
         $table->string('kata_id')->index();
         $table
            ->integer('tag_id')
            ->unsigned()
            ->index();
         $table->unique(['kata_id', 'tag_id']);
      });
   }

   public function down()
   {
      Schema::dropIfExists('tags');
      Schema::dropIfExists('kata_tag');
   }
}
