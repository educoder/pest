<?php

/**
 * Created by PhpStorm.
 * User: hanov
 * Date: 30.01.14
 * Time: 14:01
 */

include_once __DIR__ . '/../PestJSON.php';

$endpoints = [
    'twitter' => [
        'baseUrl' => 'https://api.twitter.com',
        'url' => '/1.1/help/configuration.json',
    ],
];

try {
    $rest = new PestJSON($endpoints['twitter']['baseUrl']);
    $rest->get($endpoints['twitter']['url']);
} catch (\Exception $e) {
    echo PHP_EOL,'this exception\'s messages you must be decoded',PHP_EOL;
    print_r(['type' => get_class($e), 'message' => $e->getMessage(), 'code' => $e->getCode()]);
}
