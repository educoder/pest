<?php

/**
 * These PestXML usage examples were written for the Rollcall REST service 
 * (see https://github.com/educoder/rollcall)
 **/

require_once '../PestXML.php';

$pest = new PestXML('http://localhost:3000');

// Retrieve and iterate over the list of all Users
$users = $pest->get('/users.xml');

foreach($users->user as $user) {
   echo $user->{'display-name'}." (".$user->username.")\n";
}
echo "\n";

// Create a new User 
$data = array(
  'user' => array(
    'username' => "jcricket",
    'password' => "pinocchio",
    'display_name' => "Jiminy Cricket",
    'kind' => "Student"
  ) 
);

$user = $pest->post('/users.xml', $data);

echo "New User's ID: ".$user->id."\n";
echo "\n";


// Update the newly created User's attributes
$data = array(
  'user' => array(
    'kind' => "Instructor",
    'metadata' => array(
      'gender' => 'male',
      'age' => 30
    )
  ) 
);

$pest->put('/users/'.$user->id.'.xml', $data);


// Retrieve the User
$user = $pest->get('/users/'.$user->id.'.xml');
echo "User XML: \n";
echo $user->asXML();
echo "\n";
echo "Name: ".$user->{'display-name'}."\n";
echo "Kind: ".$user->kind."\n";
echo "Age: ".$user->metadata->age."\n";
echo "\n";

// Delete the User
$user = $pest->delete('/users/'.$user->id.'.xml');


// Try to create a User with invalid data (missing username)
$data = array(
  'user' => array(
    'password' => "pinocchio",
    'display_name' => "Jiminy Cricket",
    'kind' => "Student"
  ) 
);

try {
  $user = $pest->post('/users.xml', $data);
} catch (Pest_InvalidRecord $e) {
  echo $e->getMessage();
  echo "\n";
}

?>