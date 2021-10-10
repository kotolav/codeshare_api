<?php

namespace App\Classes\HttpClient;

use App\Classes\HttpClient\Exception\HTTPRequestError;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class GuzzleHttpSessionClient implements HttpSessionClientInterface
{
   private Client $httpClient;
   private CookieJar $cookieJar;

   public function __construct($options = [])
   {
      $this->cookieJar = new CookieJar();
      $this->createHttpClient($options);
   }

   private function createHttpClient($options)
   {
      $defaultOptions = [
         'cookies' => &$this->cookieJar, // Assign by reference because there is no other way to communicate with cookies after
         'allow_redirects' => false,
      ];
      $options = array_merge($defaultOptions, $options);
      $this->httpClient = new Client($options);
   }

   /**
    * @param string $cookies
    *
    * @return array
    */
   private function splitCookiesToArray(string $cookies): array
   {
      return collect(explode(';', $cookies))
         ->mapWithKeys(function ($key) {
            [$key, $value] = explode('=', trim($key));

            return [$key => $value];
         })
         ->toArray();
   }

   public function setCookies($cookies, $domain)
   {
      $cookiesArray = $this->splitCookiesToArray($cookies);
      $this->cookieJar = CookieJar::fromArray($cookiesArray, $domain);
   }

   public function get($url, array $options = []): array
   {
      return $this->request($url, 'GET', [], $options);
   }

   private function request($url, $method, $data = [], $options = []): array
   {
      $requestOptions = $options;
      if ($method === 'POST') {
         $requestOptions['form_params'] = $data;
      }

      $result = [0, [], ''];
      try {
         $response = $this->httpClient->request($method, $url, $requestOptions);
         $result = [
            $response->getStatusCode(),
            collect($response->getHeaders())
               ->mapWithKeys(function ($item, $key) {
                  return [mb_strtolower($key) => collect($item)->first() ?? ''];
               })
               ->toArray(),
            optional($response->getBody())->getContents(),
         ];
      } catch (\Throwable $exception) {
         throw new HTTPRequestError($exception->getMessage());
      }

      return $result;
   }

   public function post($url, array $postData = [], array $options = []): array
   {
      return $this->request($url, 'POST', $postData, $options);
   }
}
