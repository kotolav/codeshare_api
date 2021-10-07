<?php

namespace App\Classes\Codewars\HtmlParser;

interface CodewarsHTMLParserInterface
{
   /**
    * authenticity_token value from https://www.codewars.com/users/sign_in
    * @param $html
    *
    * @return string
    */
   public function getAuthToken($html): string;

   /**
    * User login value from https://www.codewars.com/users/edit
    * @param $settingsPageHtml
    *
    * @return string
    */
   public function getUserLogin($settingsPageHtml): string;

   /**
    * Error message after failed auth from https://www.codewars.com/users/sign_in
    * @param $html
    *
    * @return string
    */
   public function getErrorAuthMessage($html): string;

   /**
    * Completed solutions count from https://www.codewars.com/users/%username%/completed_solutions
    * @param $html
    *
    * @return int
    */
   public function getCompleteSolutionsCount($html): int;

   /**
    * Get solutions from https://www.codewars.com/users/%username%/completed_solutions?page=%page_number%
    *
    * @param $html
    *
    * @return array ['name' => string, 'id' => string, 'rank' => number, solutions => ['language' => string, 'code' => string, date => number | null]]
    */
   public function getCompleteSolutionsFromPage($html): array;
}
