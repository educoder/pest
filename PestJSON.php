<?php

require_once 'Pest.php';

/**
 * Small Pest addition by Egbert Teeselink (http://www.github.com/eteeselink)
 *
 * Pest is a REST client for PHP.
 * PestJSON adds JSON-specific functionality to Pest, automatically converting
 * JSON data resturned from REST services into PHP arrays and vice versa.
 * 
 * In other words, while Pest's get/post/put/delete calls return raw strings,
 * PestJSON return (associative) arrays.
 * 
 * In case of >= 400 status codes, an exception is thrown with $e->getMessage() 
 * containing the error message that the server produced. User code will have to 
 * json_decode() that manually, if applicable, because the PHP Exception base
 * class does not accept arrays for the exception message and some JSON/REST servers
 * do not produce nice JSON 
 *
 * See http://github.com/educoder/pest for details.
 *
 * This code is licensed for use, modification, and distribution
 * under the terms of the MIT License (see http://en.wikipedia.org/wiki/MIT_License)
 */
class PestJSON extends Pest
{
  public function post($url, $data, $headers=array()) {
    return parent::post($url, json_encode($data), $headers);
  }
  
  public function put($url, $data, $headers=array()) {
    return parent::put($url, json_encode($data), $headers);
  }

  protected function prepRequest($opts, $url) {
    $opts[CURLOPT_HTTPHEADER][] = 'Accept: application/json';
    $opts[CURLOPT_HTTPHEADER][] = 'Content-Type: application/json';
    return parent::prepRequest($opts, $url);
  }

  public function processBody($body) {
    return json_decode($body, true);
  }
}
