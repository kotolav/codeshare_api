<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolversTable extends Migration
{
   public function up()
   {
      Schema::create('solvers', function (Blueprint $table) {
         $table->integer('id')->primary();
         $table->string('nick')->nullable();
         $table->text('about')->nullable();
      });
   }

   public function down()
   {
      Schema::dropIfExists('solvers');
   }
}
