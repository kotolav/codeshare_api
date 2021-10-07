<?php

namespace App\Providers;

use App\Classes\Codewars\CodewarsCrawler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
   public $singletons = [
      //      CodewarsCrawler::class => CodewarsCrawler::class,
   ];

   /**
    * Register any application services.
    *
    * @return void
    */
   public function register()
   {
      //
   }

   /**
    * Bootstrap any application services.
    *
    * @return void
    */
   public function boot()
   {
      //
   }
}
