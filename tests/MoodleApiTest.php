<?php

use PHPUnit\Framework\TestCase;
use Zulkarnen\MoodleApi;

class MoodleApiTest extends TestCase
{
    private $actualApi;
    private $moodleRestMock;
    private $moodleApi;
    protected function setUp(): void
    {
        $token = "1048c53d2b305ab071fa5a2ade26d6c8";
        $serverAddress = "http://moodle:8080/webservice/rest/server.php";
        $this->actualApi = new MoodleApi($token, $serverAddress);
        $this->moodleRestMock = $this->createMock(MoodleRest::class);
        $this->moodleApi = new MoodleApi('testToken', 'http://testserver', $this->moodleRestMock);
    }
    public function testGetCourseGradeCategories()
    {
        $courseId = 2;
        $result = $this->actualApi->getCourseGradeCategories($courseId);
        $this->assertIsArray($result);
        $this->assertIsArray($result['gradecategories']);
        $this->assertArrayHasKey("gradecategories", $result);
        $this->assertArrayHasKey("course", $result);
        $this->assertEquals($courseId, $result['course']['id']);
        $this->assertCount(4, $result["gradecategories"]);
    }

    public function testGetGradeReportSelfEnrol()
    {
        $courseid = 2;
        $password = 12345;
        $result = $this->actualApi->getGradeReportSelfEnrol(
            $courseid,
            $password
        );
        $this->assertIsArray($result);
        $this->assertArrayHasKey("user", $result[0]);
        $this->assertArrayHasKey("gradeitems", $result[0]);
        $this->assertArrayHasKey("category_totals", $result[0]);
        $this->assertArrayHasKey("course_total", $result[0]);
    }

    public function testGetGradeReportCourse()
    {
        $courseid = 2;
        $result = $this->actualApi->getGradeReportCourse($courseid);
        $this->assertIsArray($result);
        $this->assertArrayHasKey("user", $result[0]);
        $this->assertArrayHasKey("gradeitems", $result[0]);
        $this->assertArrayHasKey("category_totals", $result[0]);
        $this->assertArrayHasKey("course_total", $result[0]);
    }

    public function testGetSelfEnrolCourse()
    {
        $courseid = 2;
        $password = 12345;
        $result = $this->actualApi->getSelfEnrolCourse(
            $courseid,
            $password
        );
        $this->assertIsArray($result);
        $this->assertArrayHasKey("course", $result);
        $this->assertArrayHasKey("selfenrol", $result);
        $this->assertArrayHasKey("users", $result);
        $this->assertIsArray($result['users']);
        $this->assertEquals(12345, $result['selfenrol']['password']);
        $this->assertEquals(2, $result['course']['id']);
    }



    public function testRegisterUser()
    {
        $userData = [
            'username' => 'johndoe',
            'password' => 'Test@12345',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
            'auth' => 'manual',
            'idnumber' => '123456',
            'lang' => 'en',
            'timezone' => 'Asia/Jakarta',
        ];

        $expectedResponse = [
            'id' => 1,
            'username' => 'johndoe',
        ];

        // Configure the MoodleRest mock to return the expected response.
        $this->moodleRestMock->expects($this->once())
            ->method('request')
            ->with('core_user_create_users', ['users' => [$userData]])
            ->willReturn($expectedResponse);

        // Call the registerUser method and check the result.
        $response = $this->moodleApi->registerUser($userData);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetCourse()
    {
        $courseid = 2;
        $result = $this->actualApi->getCourse($courseid);
        $this->assertIsArray($result);
        $this->assertArrayHasKey("id", $result);
        $this->assertArrayHasKey("fullname", $result);
        $this->assertEquals(2, $result['id']);
        $this->assertIsString($result['fullname']);
    }


    public function testGetCourses()
    {
        $result = $this->actualApi->getCourses();
        $this->assertIsArray($result);
        $this->assertArrayHasKey("id", $result[0]);
        $this->assertArrayHasKey("fullname", $result[0]);
        $this->assertIsString($result[0]['fullname']);
    }

}
