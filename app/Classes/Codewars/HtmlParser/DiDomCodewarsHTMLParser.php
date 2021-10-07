<?php

namespace App\Classes\Codewars\HtmlParser;

use App\Classes\Codewars\HtmlParser\Exception\ParseResponseException;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use DiDom\Query;
use Illuminate\Support\Str;
use Throwable;

class DiDomCodewarsHTMLParser implements CodewarsHTMLParserInterface
{
   /**
    * @throws ParseResponseException
    */
   public function getAuthToken($html): string
   {
      try {
         $document = new Document($html);
         $token = $document
            ->first('form#new_user input[name="authenticity_token"]')
            ->attr('value');
      } catch (Throwable $e) {
         throw new ParseResponseException(
            'Can not find authenticity_token param'
         );
      }

      return $token;
   }

   /**
    * Get user login from settings page
    *
    * @param $settingsPageHtml
    *
    * @return string
    * @throws ParseResponseException
    */
   public function getUserLogin($settingsPageHtml): string
   {
      try {
         $document = new Document($settingsPageHtml);
         $username = $document->first('input#user_username')->attr('value');
      } catch (Throwable $e) {
         throw new ParseResponseException('Can not find username param');
      }
      return $username;
   }

   /**
    * Get complete solutions count
    *
    * @param $html
    *
    * @return int
    * @throws ParseResponseException
    */
   public function getCompleteSolutionsCount($html): int
   {
      try {
         $document = new Document($html);
         $completedCountText = $document
            ->first("//a[contains(text(),'Completed')]", Query::TYPE_XPATH)
            ->text();
         return intval(Str::between($completedCountText, '(', ')'));
      } catch (Throwable $e) {
         throw new ParseResponseException(
            'Can not find completed solutions count'
         );
      }
   }

   /**
    * @throws ParseResponseException
    */
   public function getCompleteSolutionsFromPage($html): array
   {
      $completedSolutions = [];
      try {
         $document = new Document($html);
         $solutionBlocks = $document->find('.list-item-solutions');

         foreach ($solutionBlocks as $solutionBlock) {
            $completedSolutions[] = $this->processSolution($solutionBlock);
         }
      } catch (Throwable $exception) {
         throw new ParseResponseException('Can not parse solutions from page');
      }

      return $completedSolutions;
   }

   private function processSolution($element): array
   {
      $titleSection = $element->first('.item-title');

      $kataLinkTag = $titleSection->first('a');
      $kataTitle = $kataLinkTag->text();
      $kataLink = Str::afterLast($kataLinkTag->attr('href'), '/kata/');

      $kataRankTag = $titleSection->first('.inner-small-hex span');
      if ($kataRankTag) {
         $kataRank = Str::before($kataRankTag->text(), ' kyu');
      } else {
         $kataRank =
            optional($titleSection->first('.tag span'))->text() ?? 'none';
      }

      $solutionsTags = $element->find(
         '//h6/following-sibling::*[1]/self::div//code',
         Query::TYPE_XPATH
      );

      $solutions = [];
      foreach ($solutionsTags as $solutionCodeTag) {
         $solution = [
            'language' => $solutionCodeTag->attr('data-language'),
            'code' => $solutionCodeTag->text(),
            'date' => $this->getSubmitSolutionDate($solutionCodeTag),
         ];
         $solutions[] = $solution;
      }

      return [
         'id' => $kataLink,
         'name' => $kataTitle,
         'rank' => $kataRank,
         'solutions' => $solutions,
      ];
   }

   /**
    * @throws InvalidSelectorException
    */
   public function getErrorAuthMessage($html): string
   {
      $document = new Document($html);
      $errorBox = $document->first('.alert-box.error');
      if ($errorBox) {
         return $errorBox->text();
      } else {
         return '';
      }
   }

   private function getSubmitSolutionDate($solutionDomElement)
   {
      try {
         return strtotime(
            $solutionDomElement
               ->closest('div')
               ->nextSibling('ul')
               ->first('time-ago')
               ->attr('datetime')
         );
      } catch (Throwable $e) {
         return null;
      }
   }
}
