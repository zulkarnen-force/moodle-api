<?php

//step 1: include the composer autoload file
require_once "vendor/autoload.php";
require "get_token.php";

use Zulkarnen\MoodleApi;
use GetToken;

function config() {
    $tokenInstance = new GetToken();
    return [
        'baseUrl' => $tokenInstance->baseUrl,
        'token' => $tokenInstance->token,
        'username' => $tokenInstance->username
    ];
}

// get grades report by course id
function getEnrolledUsersInCourse() {
    $courseId = $_GET['courseid'] ?? 1;
    $config = config();
    $baseUrl = $config['baseUrl'];
    $token = $config['token'];  

    // Create MoodleApi instance
    $moodleApi = new MoodleApi(
        $token,
        $baseUrl
    );

    // Fetch grades report
    //echo "get Grades Report for Course ID {$courseId}:" . PHP_EOL;
    header('Content-Type: application/json');
    echo json_encode($moodleApi->getEnrolledUsers($courseId));
   
}   

getEnrolledUsersInCourse();

