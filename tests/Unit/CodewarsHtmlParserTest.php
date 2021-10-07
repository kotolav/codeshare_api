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
         './tests/TestDataHTML/CodewarsAuthPage.html'
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
         './tests/TestDataHTML/SolvedKataPageZeroPage.html'
      );
      $solutions = $this->parser->getCompleteSolutionsFromPage($sourceData);
      $this->assertEquals(15, count($solutions));
   }

   public function testGetSolutionsCount()
   {
      $sourceData = file_get_contents(
         './tests/TestDataHTML/SolvedKataPageZeroPage.html'
      );
      $solutionsCount = $this->parser->getCompleteSolutionsCount($sourceData);
      $this->assertEquals(124, $solutionsCount);
   }
}
