<?php

require_once 'Pest.php';

/**
 * Pest is a REST client for PHP.
 * PestJSON adds JSON-specific functionality to Pest, automatically converting
 * JSON data resturned from REST services into PHP arrays and vice versa.
 * 
 * In other words, while Pest's get/post/put/delete calls return raw strings,
 * PestJSON return (associative) arrays.
 *
 * See http://github.com/educoder/pest for details.
 *
 * This code is licensed for use, modification, and distribution
 * under the terms of the MIT License (see http://en.wikipedia.org/wiki/MIT_License)
 */
public class PestJSON extends Pest
{
  public function post($url, $data) {
    parent::post($url, json_encode($data));
  }
  
  public function put($url, $data) {
    parent::put($url, json_encode($data));
  }

  protected function prepRequest($opts, $url) {
    $opts[CURLOPT_HTTPHEADER][] = 'Content-Type: application/json';
  }

  public function processBody($body) {
    return json_decode($body, true);
  }
  
  public function processError($body) {
    return json_decode($body, true);
  }

}