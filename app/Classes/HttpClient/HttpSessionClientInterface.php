<?php

namespace App\Classes\HttpClient;

interface HttpSessionClientInterface
{
   /**
    * Load cookies for a given domain
    * @param string $cookies - 'key=value; key2=value2'
    * @param string $domain
    *
    * @return mixed
    */
   public function setCookies(string $cookies, string $domain);

   /**
    * Send GET request. Response with ['statusCode', 'headers', 'body'].
    * Header has lowercase keys and values as array ['content-type' => ['text/html; charset=utf-8']]
    *
    * @param $url
    * @param array $options
    *
    * @return array
    */
   public function get($url, array $options = []): array;

   /**
    * Send POST request. Response with ['statusCode', 'headers', 'body'].
    * Header has lowercase keys and values as array ['content-type' => ['text/html; charset=utf-8']]
    *
    * @param $url
    * @param array $postData - ['key' => 'value', 'key2' => 'value2']
    * @param array $options
    *
    * @return array
    */
   public function post($url, array $postData = [], array $options = []): array;
}
