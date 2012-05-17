<?php
/**
 * This Pest usage example accesses the PSWinCom Intouch REST API.
 * API documentation: http://wiki.pswin.com/Intouch-REST-API.ashx
 **/
 
// Intouch auth info
$user = "USERNAME";
$logindomain = "DOMAIN";
$password = "PASSWORD";

// Test data
$contactId; // Received after creating the contact.
$firstname = "John";
$lastname = "Doe";
$phonenumber = "4712345678"; // Modify to a valid mobile number.
$description = "This info was input with a REST PUT request";
$messagetext = "Hello! This message was sent by posting to the Intouch REST API.";
$sendernumber = "IntouchAPI"; // Must be a valid sendernumber set up for your account

 // Setting up Pest with URL for the Intouch API and basic HTTP authentication 
require_once '../Pest.php';
$pest = new Pest('http://intouchapi.pswin.com/1/');
$pest->setupAuth($user ."@". $logindomain, $password);
$pest->curl_opts[CURLOPT_FOLLOWLOCATION] = false; // Not supported on hosts running safe_mode!


echo "<h1>1. Creating a contact in the address book</h1>";
try 
{		    
	$contactdata = array(
    'Firstname' => $firstname,
    'Lastname' => $lastname,
    'PhoneNumber' => $phonenumber
		);
		
  $contactjson = $pest->post('/contacts', $contactdata);
  $contactarray = json_decode($contactjson,true);
  $contactId = $contactarray[ContactId];
  echo "<br>Created contact with id: " . $contactId;
} 
catch (Exception $e) 
{
    echo "<br>Caught exception when creating contact : " .  $e->getMessage() . "<br>";
}


echo "<h1>2. Getting the contact by its phonenumber and updating it.</h1>";
try 
{		    
  $contactjson = $pest->get('/contacts/phonenumber/' . $phonenumber);
  $contactarray = json_decode($contactjson,true);
  echo "<br>Retrieved contact with id: " .  $contactarray[ContactId] . ", should be identical with " . $contactId . " from part 1.<br>";
  
  $contactarray[Description] = $description;  
  $contactjson = json_encode($contactarray);
     
  $pest->put('/contacts/' . $contactarray[ContactId], $contactjson);
  echo "<br>Updated contact<br>";
} 
catch (Exception $e) 
{
    echo "<br>Caught exception when retrieving or updating contact : " .  $e->getMessage() . "<br>";
}


echo "<h1>3. Sending a message to the contact</h1>";
try 
{		    
	$messagedata = array(
    'Receivers' => "/contacts/" . $contactId,
    'SenderNumber' => $sendernumber,
    'Text' => $messagetext
		);
		
  $messagejson = $pest->post('/messages', $messagedata);
  $messagearray = json_decode($messagejson,true);
  echo "<br>Message was sent! MessageId: " .  $messagearray[MessageId];
} 
catch (Exception $e) 
{
    echo "<br>Caught exception when sending message : " .  $e->getMessage() . "<br>";
}


echo "<h1>4. Deleting contact.</h1>";
try 
{		    
  $pest->delete('/contacts/' . $contactId);
  echo "<br>Deleted contact.<br>";
} 
catch (Exception $e) 
{
    echo "<br>Caught exception when deleting contact : " .  $e->getMessage() . "<br>";
}
?>