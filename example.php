<?php
require_once 'vendor/autoload.php'; // Make sure you include the Composer autoload file
use Zulkarnen\MoodleApi;
$elearning = new MoodleApi("1048c53d2b305ab071fa5a2ade26d6c8", "http://moodle:8080/webservice/rest/server.php");
$userData = [
     'username' => 'johndoe123',
     'password' => 'Test@12345',
     'firstname' => 'John',
     'lastname' => 'Doe',
     'email' => 'johndoe123@example.com',
     'auth' => 'manual', // default authentication type
     'idnumber' => '123456',
     'lang' => 'en',
     'timezone' => 'Asia/Jakarta',
];
// $response = $elearning->registerUser($userData);
$response = $elearning->getUser('email', 'johndoe123@example.com');
// $test = $elearning->getGradeReport(1);
print_r($response);
// print_r($test);
die();