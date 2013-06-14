<?php
/**
 * Pest is a REST client for PHP.
 *
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
 * If you want to have exceptions thrown when there are errors encoding or
 * decoding JSON set the `throwEncodingExceptions` property to TRUE.
 *
 * See http://github.com/educoder/pest for details.
 *
 * This code is licensed for use, modification, and distribution
 * under the terms of the MIT License (see http://en.wikipedia.org/wiki/MIT_License)
 */

require_once 'Pest.php';

class PestJSON extends Pest
{
    /**
     * @var bool Throw exceptions on JSON encoding errors?
     * @var bool Throw exceptions on JSON decoding errors?
     */
    public $throwEncodingExceptions = false;
    public $throwDecodingExceptions = false;

    /**
     * Perform an HTTP POST
     *
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return string
     */
    public function post($url, $data, $headers = array())
    {
        return parent::post($url, $this->jsonEncode($data), $headers);
    }

    /**
     * Perform HTTP PUT
     *
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return string
     */
    public function put($url, $data, $headers = array())
    {
        return parent::put($url, $this->jsonEncode($data), $headers);
    }

    /**
     * JSON encode with error checking
     *
     * @param string $data
     * @return string
     * @throws Pest_Json_Encode
     */
    public function jsonEncode($data)
    {
        $ret = json_encode($data);

        if ($this->throwEncodingExceptions
                && json_last_error() !== JSON_ERROR_NONE) {
            throw new Pest_Json_Encode(
		'Encoding error('.json_last_error().'): ' . $this->_getLastJsonErrorMessage()
            );
        }

        return $ret;
    }

    /**
     * Decode a JSON string with error checking
     *
     * @param $data
     * @param bool $asArray
     * @return mixed
     * @throws Pest_Json_Encode
     */
    public function jsonDecode($data, $asArray=true)
    {
        $ret = json_decode($data, $asArray);

        if ($this->throwDecodingExceptions
            && json_last_error() !== JSON_ERROR_NONE) {
            throw new Pest_Json_Decode(
		'Decoding error('.json_last_error().'): ' . $this->_getLastJsonErrorMessage()
            );
        }

        return $ret;
    }

    /**
     * Get last JSON error message
     *
     * @return string
     */
    protected function _getLastJsonErrorMessage()
    {
        switch (json_last_error()) {
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
	        return 'Unknown';
                break;
        }
    }

    /**
     * Process body
     * @param string $body
     * @return mixed|string
     */
    public function processBody($body)
    {
        return $this->jsonDecode($body);
    }

    /**
     * Prepare request
     *
     * @param array $opts
     * @param string $url
     * @return resource
     */
    protected function prepRequest($opts, $url)
    {
        $opts[CURLOPT_HTTPHEADER][] = 'Accept: application/json';
        $opts[CURLOPT_HTTPHEADER][] = 'Content-Type: application/json';
        return parent::prepRequest($opts, $url);
    }
}

// JSON Errors
/* decode */
class Pest_Json_Decode extends Pest_ClientError
{}

/* encode */
class Pest_Json_Encode extends Pest_ClientError
{}
