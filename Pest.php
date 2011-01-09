<?php

/**
 * Pest is a REST client for PHP.å
 *
 * See http://github.com/educoder/pest for details.
 *
 * This code is licensed for use, modification, and distribution
 * under the terms of the MIT License (see http://en.wikipedia.org/wiki/MIT_License)
 */
class Pest {
  public $curl_opts = array(
  	CURLOPT_RETURNTRANSFER => true,  // return result instead of echoing
  	CURLOPT_SSL_VERIFYPEER => false, // stop cURL from verifying the peer's certificate
  	CURLOPT_FOLLOWLOCATION => true,  // follow redirects, Location: headers
  	CURLOPT_MAXREDIRS      => 10     // but dont redirect more than 10 times
  );
  
  public $site;
  
  public $last_response;
  
  public function __construct($site) {
    if (!function_exists('curl_init')) {
  	    throw new Exception('CURL module not available! Pest requires CURL. See http://php.net/manual/en/book.curl.php');
  	}
  	
  	// eliminate trailing '/' from site URL
  	$site = preg_replace('/\/$/', '', $site);
    
    $this->site = $site;
  }
  
  public function get($path) {
    $curl = $this->curlPrep($this->curl_opts, $this->site, $path);
    $body = $this->curlRequest($curl);
    
    $body = $this->processResponse($body);
    
    return $body;
  }
  
  public function post($path, $data) {
    $data = (is_array($data)) ? http_build_query($data) : $data; 
    
    $curl_opts = $this->curl_opts;
    $curl_opts[CURLOPT_CUSTOMREQUEST] = 'POST';
    $curl_opts[CURLOPT_HTTPHEADER] = array('Content-Length: '.strlen($data));
    $curl_opts[CURLOPT_POSTFIELDS] = $data;
    
    $curl = $this->curlPrep($curl_opts, $this->site, $path);
    $body = $this->curlRequest($curl);
    
    $body = $this->processResponse($body);
    
    return $body;
  }
  
  public function put($path, $data) {
    $data = (is_array($data)) ? http_build_query($data) : $data; 
    
    $curl_opts = $this->curl_opts;
    $curl_opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
    $curl_opts[CURLOPT_HTTPHEADER] = array('Content-Length: '.strlen($data));
    $curl_opts[CURLOPT_POSTFIELDS] = $data;
    
    $curl = $this->curlPrep($curl_opts, $this->site, $path);
    $body = $this->curlRequest($curl);
    
    $body = $this->processResponse($body);
    
    return $body;
  }
  
  public function lastBody() {
    return $this->last_response['body'];
  }
  
  public function lastStatus() {
    return $this->last_response['meta']['http_code'];
  }

  
  private function curlPrep($opts, $site, $path) {
    $curl = curl_init($site . $path);
    
    foreach ($opts as $opt => $val)
      curl_setopt($curl, $opt, $val);
      
    return $curl;
  }
  
  private function curlRequest($curl) {
    $body = curl_exec($curl);
    $meta = curl_getinfo($curl);
    
    $this->last_response = array(
      'body' => $body,
      'meta' => $meta
    );
    
    curl_close($curl);
  }
  
  private function processResponse($body) {
    // Override this in classes that extend Pest.
    // The body of every GET/POST/PUT/DELETE response goes through 
    // here prior to being returned.
    return $body;
  }
}

?>