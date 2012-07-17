<?php

require_once 'Pest.php';

/**
 * Pest is a REST client for PHP.
 * PestXML adds XML-specific functionality to Pest, automatically converting
 * XML data resturned from REST services into SimpleXML objects.
 * 
 * In other words, while Pest's get/post/put/delete calls return raw strings,
 * PestXML's return SimpleXML objects.
 *
 * PestXML also attempts to derive error messages from the body of erroneous
 * responses, expecting that these too are in XML (i.e. the contents of
 * the first <error></error> tag in the response is assumed to be the error mssage) 
 *
 * See http://github.com/educoder/pest for details.
 *
 * This code is licensed for use, modification, and distribution
 * under the terms of the MIT License (see http://en.wikipedia.org/wiki/MIT_License)
 */
class PestXML extends Pest {
  public function processBody($body) {
    libxml_use_internal_errors(true);
    if (empty($body) || preg_match('/^\s+$/', $body))
      return null;
    
    $xml = simplexml_load_string($body);
    
    if (!$xml) {
      $err = "Couldn't parse XML response because:\n";
      $xml_errors = libxml_get_errors();
      libxml_clear_errors();
      if(!empty($xml_errors))
      {
        foreach($xml_errors as $xml_err)
          $err .= "\n    - " . $xml_err->message;
        $err .= "\nThe response was:\n";
        $err .= $body;
        throw new PestXML_Exception($err);
      }
    }
    
    return $xml;
  }
  
  public function processError($body) {
    try {
      $xml = $this->processBody($body);
      if (!$xml)
        return $body;
      
      $error = $xml->xpath('//error');
      
      if ($error && $error[0])
        return strval($error[0]);
      else
        return $body;
    } catch (PestXML_Exception $e) {
      return $body;
    }
  }
}

class PestXML_Exception extends Pest_Exception { }

?>