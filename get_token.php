<?php
require_once "vendor/autoload.php"; // Make sure you include the Composer autoload file
use Zulkarnen\MoodleAuth;

class GetToken {
    public $token;
    public $baseUrl;
    public $username;

    public function __construct() {
        $this->token = $this->fetchToken();
    }

    private function fetchToken() {

        // Your Moodle credentials (use a dedicated service account if possible)
        $baseUrl = 'https://elearning.uad.ac.id';
        $username = '';
        $password = '';
        $serviceName = 'moodle_mobile_app'; // Must match the external service name in Moodle
        
        $this->baseUrl = $baseUrl;
        $this->username = $username;
        return MoodleAuth::fetchToken($baseUrl, $username, $password, $serviceName);
    }
}