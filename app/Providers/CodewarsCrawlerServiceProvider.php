<?php

namespace App\Providers;

use App\Classes\Codewars\CodewarsCrawler;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CodewarsCrawlerServiceProvider extends ServiceProvider implements
   DeferrableProvider
{
   public function register()
   {
      $this->app->singleton(CodewarsCrawler::class, CodewarsCrawler::class);
   }

   public function boot()
   {
      //
   }

   public function provides()
   {
      return [CodewarsCrawler::class];
   }
}
