<?php

use PHPUnit\Framework\TestCase;
use Zulkarnen\MoodleApi;

class MoodleApiTest extends TestCase
{
    private $moodleApi;

    protected function setUp(): void
    {
        $token = "1048c53d2b305ab071fa5a2ade26d6c8";
        $serverAddress = "http://moodle:8080/webservice/rest/server.php";
        $this->moodleApi = new MoodleApi($token, $serverAddress);
    }
    public function testElearningGetCourseGradeCategories()
    {
        $result = $this->moodleApi->elearningGetCourseGradeCategories(2);
        $this->assertIsArray($result);
        $this->assertArrayHasKey("gradecategories", $result);
        $this->assertArrayHasKey("course", $result);
        $this->assertEquals(2, $result["course"]["id"]);
        $this->assertCount(4, $result["gradecategories"]);
    }

    public function testElearningGetGradeReportWithSelfEnrolInCourse()
    {
        $courseid = 2;
        $password = 12345;
        $result = $this->moodleApi->elearningGetGradeReportWithSelfEnrolInCourse(
            $courseid,
            $password
        );
        $this->assertIsArray($result);
        $this->assertArrayHasKey("user", $result[0]);
        $this->assertArrayHasKey("gradeitems", $result[0]);
        $this->assertArrayHasKey("category_totals", $result[0]);
        $this->assertArrayHasKey("course_total", $result[0]);
    }
}
