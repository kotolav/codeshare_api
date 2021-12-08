<?php

namespace App\Classes\Codewars;

use App\Classes\Codewars\Exception\AuthFailException;
use App\Classes\Codewars\Exception\ParseException;
use App\Classes\Codewars\Exception\WrongEmailPasswordException;
use App\Classes\Codewars\HtmlParser\CodewarsHTMLParserInterface;
use App\Classes\Codewars\HtmlParser\Exception\ParseResponseException;
use App\Classes\HttpClient\HttpSessionClientInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class CodewarsCrawler
{
   private const AUTH_URL = 'https://www.codewars.com/users/sign_in';
   private const SOLUTIONS_URL = 'https://www.codewars.com/users/%s/completed_solutions?page=%s';
   private const USER_SETTINGS_URL = 'https://www.codewars.com/users/edit';
   private const API_URL = 'https://www.codewars.com/api/v1';
   private const COOKIES_DOMAIN = 'www.codewars.com';
   protected HttpSessionClientInterface $httpClient;
   protected CodewarsHTMLParserInterface $codewarsHtmlParser;
   private string $login = '';

   public function __construct(
      CodewarsHTMLParserInterface $codewarsHtmlParser,
      HttpSessionClientInterface $httpClient
   ) {
      $this->codewarsHtmlParser = $codewarsHtmlParser;
      $this->httpClient = $httpClient;
   }

   /**
    * Get solved challenges with login and password
    * @param $login
    * @param $password
    *
    * @return array
    * @throws AuthFailException
    * @throws ParseException
    * @throws ParseResponseException
    */
   public function getSolutionsWithLoginPassword($login, $password): array
   {
      return $this->getSolutions($login, $password);
   }

   /**
    * Get solved challenges with cookies
    *
    * @param string $cookies
    *
    * @return array
    * @throws AuthFailException
    * @throws ParseException
    * @throws ParseResponseException
    */
   public function getSolutionsWithCookies(string $cookies): array
   {
      return $this->getSolutions('', '', $cookies);
   }

   /**
    * @throws ParseException
    */
   public function getKataDescriptions(array $kataIds): array
   {
      $errorsCountStreak = 0;
      $errorsCountLimit = 3;
      $descriptions = [];

      foreach ($kataIds as $id) {
         try {
            $descriptions[] = $this->getKataDescriptionByAPI($id);
            $errorsCountStreak = 0;
         } catch (Throwable $exception) {
            $errorsCountStreak++;
            if ($errorsCountStreak >= $errorsCountLimit) {
               throw $exception;
            }
         }
      }

      return $descriptions;
   }

   /**
    * @return string
    */
   public function getLogin(): string
   {
      return $this->login;
   }

   /**
    * Retrieve solved challenges
    *
    * @param $login
    * @param $password
    * @param $cookies
    *
    * @return array
    * @throws AuthFailException
    * @throws ParseResponseException
    * @throws ParseException
    */
   private function getSolutions($login, $password, $cookies = null): array
   {
      try {
         $this->doAuth($cookies, $login, $password);

         return $this->getUserSolutions();
      } catch (WrongEmailPasswordException | AuthFailException | ParseResponseException $exception) {
         throw $exception;
      } catch (Throwable $exception) {
         throw new ParseException($exception->getMessage());
      }
   }

   /**
    * @param string $login
    */
   private function setLogin(string $login): void
   {
      $this->login = trim($login);
   }

   /**
    * Authorization with cookies
    *
    * @param string $cookies
    *
    * @throws AuthFailException
    * @throws WrongEmailPasswordException
    */
   private function authorizeAccountWithCookies(string $cookies): void
   {
      $this->httpClient->setCookies($cookies, self::COOKIES_DOMAIN);
      [$authStatusCode, $headers, $authResponseBody] = $this->httpClient->get(
         self::AUTH_URL
      );
      $this->continueAuth($authResponseBody, $authStatusCode, $headers);
   }

   /**
    * @throws WrongEmailPasswordException
    * @throws AuthFailException
    */
   private function continueAuth($body, $serverResponseCode, $headers): void
   {
      if (!$this->isAuthSuccessful($serverResponseCode, $headers)) {
         $errorMessage = $this->codewarsHtmlParser->getErrorAuthMessage($body);
         if (Str::contains($errorMessage, 'Invalid email or password')) {
            throw new WrongEmailPasswordException($errorMessage);
         }
         throw new AuthFailException($errorMessage ?: 'Unknown auth error');
      }
   }

   private function isAuthSuccessful(
      int $serverResponseCode,
      array $headers
   ): bool {
      $redirectLocation = collect($headers)->get('location', '');

      return $serverResponseCode === 302 &&
         Str::endsWith($redirectLocation, '/dashboard');
   }

   /**
    * Retrieve all user solutions
    */
   private function getUserSolutions(): array
   {
      $solutions = [];
      $firstPageHTML = $this->requestSolutionPage(0);

      $pageCount = $this->getSolutionPageCount($firstPageHTML);
      $solutionsPart = $this->codewarsHtmlParser->getCompleteSolutionsFromPage(
         $firstPageHTML
      );
      array_push($solutions, ...$solutionsPart);

      for ($page = 1; $page < $pageCount; $page++) {
         $html = $this->requestSolutionPage($page);
         $solutionsPart = $this->codewarsHtmlParser->getCompleteSolutionsFromPage(
            $html
         );
         // In case of miss calculation of solution pages count
         if (count($solutionsPart) === 0) {
            return $solutions;
         } else {
            array_push($solutions, ...$solutionsPart);
         }
      }

      return $solutions;
   }

   private function requestSolutionPage($pageNumber): string
   {
      $url = sprintf(self::SOLUTIONS_URL, $this->login, $pageNumber);
      [, , $response] = $this->httpClient->get($url, [
         'headers' => ['X-Requested-With' => 'XMLHttpRequest'],
      ]);

      return $response;
   }

   /**
    * Get user settings page
    * @return string
    */
   private function requestUserSettingsPage(): string
   {
      [, , $response] = $this->httpClient->get(self::USER_SETTINGS_URL);

      return $response;
   }

   /**
    * Attempt to get complete solutions count from first page
    * and calculate how many pages do we need to parse
    *
    * @param string $firstPageHTML
    *
    * @return int
    */
   private function getSolutionPageCount(string $firstPageHTML): int
   {
      try {
         $solutionsCount = $this->codewarsHtmlParser->getCompleteSolutionsCount(
            $firstPageHTML
         );
      } catch (Throwable $e) {
         $solutionsCount = 0;
      }

      return intval(ceil($solutionsCount / 15));
   }

   /**
    * Authorize account with login and password
    *
    * @param $login
    * @param $password
    *
    * @throws AuthFailException
    * @throws WrongEmailPasswordException
    */
   private function authorizeAccountWithLoginPassword($login, $password): void
   {
      // Reset cookies. Because in queue:work mode client is not recreating
      // But we need clean http client for new requests
      $this->httpClient->setCookies('', self::COOKIES_DOMAIN);
      [, , $authPageHTML] = $this->httpClient->get(self::AUTH_URL);
      $token = $this->codewarsHtmlParser->getAuthToken($authPageHTML);

      [$authStatusCode, $headers, $authResponse] = $this->httpClient->post(
         self::AUTH_URL,
         [
            'utf8' => '&#x2713;',
            'authenticity_token' => $token,
            'user' => [
               'email' => $login,
               'password' => $password,
               'remember_me' => 'false',
            ],
         ]
      );

      $this->continueAuth($authResponse, $authStatusCode, $headers);
   }

   private function setLoginFromSettingsPage(): void
   {
      $settingsPage = $this->requestUserSettingsPage();
      $username = $this->codewarsHtmlParser->getUserLogin($settingsPage);
      $this->setLogin($username);
   }

   /**
    * @param $cookies
    * @param $login
    * @param $password
    *
    * @throws AuthFailException
    * @throws WrongEmailPasswordException
    */
   private function doAuth($cookies, $login, $password): void
   {
      if ($cookies !== null) {
         $this->authorizeAccountWithCookies($cookies);
      } else {
         $this->authorizeAccountWithLoginPassword($login, $password);
      }
      $this->setLoginFromSettingsPage();
   }

   /**
    * @param $id
    *
    * @return array
    * @throws ParseException
    */
   private function getKataDescriptionByAPI($id): array
   {
      $result = Http::retry(2, 1000)->get(
         self::API_URL . '/code-challenges/' . $id
      );
      if ($result->successful()) {
         try {
            $data = $result->json();
            return [
               'id' => $data['id'],
               'name' => $data['name'],
               'category' => $data['category'],
               'description' => $data['description'],
               'tags' => $data['tags'],
               'rank' => $data['rank']['id'],
               'totalAttempts' => $data['totalAttempts'],
               'totalCompleted' => $data['totalCompleted'],
            ];
         } catch (Throwable $exception) {
            throw new ParseException(
               'Parse kata description error: ' . $exception->getMessage()
            );
         }
      } else {
         throw new ParseException('No response for Kata id: ' . $id);
      }
   }
}
