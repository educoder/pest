<?php

/**
 * This PestXML usage example pulls data from the OpenStreetMap API.
 * (see http://wiki.openstreetmap.org/wiki/API_v0.6)
 **/

require_once '../PestXML.php';

$pest = new PestXML('http://api.openstreetmap.org/api/0.6');

// Retrieve map data for the University of Toronto campus
$map = $pest->get('/map?bbox=-79.39997,43.65827,-79.39344,43.66903');

// Print all of the street names in the map
$streets = $map->xpath('//way/tag[@k="name"]');
foreach ($streets as $s) {
  echo $s['v'] . "\n";
}

?>