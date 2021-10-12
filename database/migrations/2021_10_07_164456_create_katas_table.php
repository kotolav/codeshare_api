<?php

use App\Enums\KataTaskParseType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKatasTable extends Migration
{
   public function up()
   {
      Schema::create('katas', function (Blueprint $table) {
         $table->string('id')->primary();
         $table->string('name')->default('');
         $table->string('rank')->nullable();
         $table->string('category')->nullable();
         $table->text('description')->nullable();
         $table->integer('total_attempts')->nullable();
         $table->integer('total_completed')->nullable();
         $table
            ->integer('process_status')
            ->default(KataTaskParseType::NotProcessed);
         $table->timestamps();
      });
   }

   public function down()
   {
      Schema::dropIfExists('katas');
   }
}
