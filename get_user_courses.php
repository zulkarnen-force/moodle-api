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

function getMyCourses(){
    $config = config();
    $baseUrl = $config['baseUrl'];
    $token = $config['token'];
    $username = $config['username'];

    $moodleApi = new MoodleApi($token, $baseUrl);

    $userData = $moodleApi->getUserByUsername($username);

    $userCourses = $moodleApi->getUserCourses($userData['id']);

    header('Content-Type: application/json');
    echo json_encode(
        $userCourses
    );
}

getMyCourses();
