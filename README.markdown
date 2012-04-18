Pest
====

**Pest** is a PHP client library for [RESTful](http://en.wikipedia.org/wiki/Representational_State_Transfer) 
web services.

Unlike [Zend_Rest_Client](http://framework.zend.com/manual/en/zend.rest.client.html), which is not 
really a "REST" client at all (more like RPC-over-HTTP), Pest supports the four REST verbs 
(GET/POST/PUT/DELETE) and pays attention to HTTP response status codes.


Basic Example
-------------

Pest's get/post/put/delete() return the raw response body as a string.
See the info on PestXML (below) if you're working with XML-based REST services and
PestJSON if you're working with JSON.

    <?php
    require 'Pest.php';

    $pest = new Pest('http://example.com');

    $thing = $pest->get('/things');

    $thing = $pest->post('/things', 
    	array(
    		'name' => "Foo",
    		'colour' => "Red"
    	)
    );

    $thing = $pest->put('/things/15',
    	array(
    		'colour' => "Blue"
    	)
    );

    $pest->delete('/things/15');

    ?>

Responses with error status codes (4xx and 5xx) raise exceptions.

    <?php

    try {
    	$thing = $pest->get('/things/18');
    } catch (Pest_NotFound $e) {
    	// 404
    	echo "Thing with ID 18 doesn't exist!";
    }

    try {
    	$thing = $pest->post('/things',  array('colour' => "Red"));
    } catch (Pest_InvalidRecord $e) {
    	// 422
    	echo "Data for Thing is invalid because: ".$e->getMessage();
    }

    ?>

PestXML
-------

**PestXML** is an XML-centric version of Pest, specifically targeted at REST services that 
return XML data. Rather than returning the raw response body as a string, PestXML will
try to parse the service's response into a [SimpleXML](http://php.net/manual/en/book.simplexml.php) object.

	<?php
	require 'PestXML.php';

	$pest = new Pest('http://example.com');

	$things = $pest->get('/things.xml');

	$colours = $things->xpath('//colour');
	foreach($colours as $colour) {
		echo $colour."\n";
	}

	?>

Similarly, **PestJSON** is a JSON-centric version of Pest.

Much more detailed examples are available in the `examples` directory:

* [Rollcall Example](http://github.com/educoder/pest/blob/master/examples/rollcall_example.php)
* [OpenStreetMap Example](http://github.com/educoder/pest/blob/master/examples/open_street_map_example.php)


TODO
----

* Authentication
* Follow Redirects


License
-------

Copyright (C) 2011 by University of Toronto

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.