<?php

namespace App\Providers;

use App\Classes\Codewars\HtmlParser\CodewarsHTMLParserInterface;
use App\Classes\Codewars\HtmlParser\DiDomCodewarsHTMLParser;
use App\Classes\HttpClient\GuzzleHttpSessionClient;
use App\Classes\HttpClient\HttpSessionClientInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
   public $singletons = [
      HttpSessionClientInterface::class => GuzzleHttpSessionClient::class,
      CodewarsHTMLParserInterface::class => DiDomCodewarsHTMLParser::class,
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
