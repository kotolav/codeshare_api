<?php

namespace Tests\Unit;

use App\Classes\Codewars\HtmlParser\DiDomCodewarsHTMLParser;
use App\Classes\Codewars\HtmlParser\Exception\ParseResponseException;
use PHPUnit\Framework\TestCase;

class CodewarsHtmlParserTest extends TestCase
{
   private $parser;

   public function setUp(): void
   {
      parent::setUp();
      $this->parser = app(DiDomCodewarsHTMLParser::class);
   }

   public function testGetAuthToken()
   {
      $sourceData = file_get_contents(
         'tests/TestDataHTML/CodewarsAuthPage.html'
      );
      $token = $this->parser->getAuthToken($sourceData);
      $this->assertEquals(
         'bGXJr1tim9AjLwHAK9PDCnf6HQdDpnvRQutuoPO2trP6LPdJWoEcF5Rce3IkkrBwX3Cw6o1UfiYzDE6YwT9b+Q==',
         $token,
         'auth_token is incorrect'
      );
   }

   public function testGetAuthTokenOnEmptyHtml()
   {
      $this->expectException(ParseResponseException::class);
      $this->parser->getAuthToken('');
   }

   public function testGetAuthTokenOnNull()
   {
      $this->expectException(ParseResponseException::class);
      $this->parser->getAuthToken(null);
   }

   public function testGetCompletedSolutionsFromPage()
   {
      $sourceData = file_get_contents(
         'tests/TestDataHTML/SolvedKataPageZeroPage.html'
      );
      $solutions = $this->parser->getCompleteSolutionsFromPage($sourceData);
      $this->assertCount(15, $solutions);
   }

   public function testGetSolutionsCount()
   {
      $sourceData = file_get_contents(
         'tests/TestDataHTML/SolvedKataPageZeroPage.html'
      );
      $solutionsCount = $this->parser->getCompleteSolutionsCount($sourceData);
      $this->assertEquals(124, $solutionsCount);
   }

   public function testGetSolutionsCountForDifferentLanguages()
   {
      $sourceData = file_get_contents(
         'tests/TestDataHTML/SolvedKataPageZeroPage.html'
      );
      $solutions = $this->parser->getCompleteSolutionsFromPage($sourceData);
      $this->assertCount(2, $solutions[1]['solutions']);
   }

   public function testGetSolutionLanguageForDifferentLanguagesSolution()
   {
      $sourceData = file_get_contents(
         'tests/TestDataHTML/SolvedKataPageZeroPage.html'
      );
      $solutions = $this->parser->getCompleteSolutionsFromPage($sourceData);
      $this->assertEquals(
         'typescript',
         $solutions[1]['solutions'][0]['language']
      );
      $this->assertEquals(
         'javascript',
         $solutions[1]['solutions'][1]['language']
      );
   }

   public function testGetSolutionsCountForSameLanguage()
   {
      $sourceData = file_get_contents(
         'tests/TestDataHTML/SolvedKataPageZeroPage.html'
      );
      $solutions = $this->parser->getCompleteSolutionsFromPage($sourceData);
      $this->assertCount(3, $solutions[7]['solutions']);
   }

   public function testGetSolutionLanguagesForSameLanguagesSolution()
   {
      $sourceData = file_get_contents(
         'tests/TestDataHTML/SolvedKataPageZeroPage.html'
      );
      $solutions = $this->parser->getCompleteSolutionsFromPage($sourceData);
      $this->assertEquals(
         'javascript',
         $solutions[7]['solutions'][0]['language']
      );
      $this->assertEquals(
         'javascript',
         $solutions[7]['solutions'][1]['language']
      );
      $this->assertEquals(
         'javascript',
         $solutions[7]['solutions'][2]['language']
      );
   }
}
