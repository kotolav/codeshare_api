<?php

namespace App\Http\Controllers;

use App\Classes\Codewars\CodewarsCrawler;

class HomeController extends Controller
{
   public function index(CodewarsCrawler $parser)
   {
      return 'It\'s alive!';
   }
}
