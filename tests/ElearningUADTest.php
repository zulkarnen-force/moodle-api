<?php

use ElearningUAD\ElearningUAD;
use PHPUnit\Framework\TestCase;

class ElearningUADTest extends TestCase
{
    private $elearning;

    protected function setUp(): void
    {
        // Set up mock data for testing
        $this->elearning = new ElearningUAD('test_token', 'http://moodle.test/webservice/rest/server.php');
    }

    public function testGetUserProfiles()
    {
        $userList = [
            ['userid' => 1, 'courseid' => 2],
            ['userid' => 2, 'courseid' => 2],
        ];

        // Mock the request and response
        $response = $this->elearning->getUserProfiles($userList);

        // Assuming response contains user profiles
        $this->assertIsArray($response);
        $this->assertNotEmpty($response);
    }

    public function testRegisterUser()
    {
        $userData = [
            'username' => 'johndoe',
            'password' => 'password123',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
        ];

        $response = $this->elearning->registerUser($userData);

        $this->assertIsArray($response);
        $this->assertEquals($response['username'], 'johndoe');
    }
}
