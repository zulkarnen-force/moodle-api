<?php
require_once 'vendor/autoload.php'; // Make sure you include the Composer autoload file
use Zulkarnen\MoodleApi;
$elearning = new MoodleApi("1048c53d2b305ab071fa5a2ade26d6c8", "http://moodle:8080/webservice/rest/server.php");
$userData = [
     'username' => 'zulkarnen',
     'password' => 'Password@123',
     'firstname' => 'Zul',
     'lastname' => 'Karnen',
     'email' => 'zulkarnen@example.com',
     'auth' => 'manual', // default authentication type
     'idnumber' => '1900016072',
     'lang' => 'en',
     'timezone' => 'Asia/Jakarta',
];
// Optional settings for the grade category
$options = [
     'aggregation' => 10, // e.g., Simple weighted mean of grades
     'itemname' => 'Assignment Grades',
     'gradetype' => 1, // Value-based grading
     'grademax' => 100,
     'grademin' => 0,
     'gradepass' => 50,
     'parentcategoryid' => 5, // If this category belongs to a parent category
];
// $registerUser = $elearning->registerUser($userData);
// print_r($registerUser);
// $response = $elearning->getUser('email', 'johndoe123@example.com');
// $getStudentsInCourse = $elearning->getStudentsInCourse(2);
// print_r($getStudentsInCourse);
// $usersInCourse = $elearning->getStudentsInCourse(2);
// $courses = $elearning->getCourses();
// print_r($courses);
// $courseById = $elearning->getCourse(2);
// $gradeCategories = $elearning->getGradesReport(2);
// print_r($gradeCategories);
// $addStudentToCourse = $elearning->addStudentToCourse(5, 2);
// $grades = $elearning->getGradesReport(2);
// $course = $elearning->getCourseCategories();
// $some = $elearning->getGradesReportByCategory(2);
// $createGradeCategory = $elearning->getGradeCategories(2);
// $createGradeCategory = print_r($createGradeCategory);
// print_r($grades);
// print_r($some);
// print_r($gradeCategories);
// print_r($addStudentToCourse);
// print_r($courseById);
// print_r($courses);
// print_r($usersInCourse);
// print_r($response);
// print_r($test);
// print_r($userData);
// $usersByIds = $elearning->getUser('id', 3);
// print_r($usersByIds);
// $enrolSelfUser = $elearning->enrolSelfEnrolUser(3, 3, 'SOMEENROLKEY');
// print_r($enrolSelfUser);
// $getLocal = $elearning->getLocal();
// print_r($getLocal);
// $local_gradecategories_get_grade_categories = $elearning->local_gradecategories_get_grade_categories(2);
// print_r($local_gradecategories_get_grade_categories);
// $elearning_uad_grade_student = $elearning->elearning_uad_grade_student(2);
// print_r($elearning_uad_grade_student);
// $elearningGetCourse = $elearning->elearningGetCourseGradeCategories(2);
// print_r($elearningGetCourse);
// $elearningGetGradesInCourse = $elearning->elearningGetGradesInCourse(2);
// print_r($elearningGetGradesInCourse);
// $elearningGetSelfenrolUsersInCourse = $elearning->elearningGetSelfenrolUsersInCourse(2, 12345);
// print_r($elearningGetSelfenrolUsersInCourse);
// 
$elearningGetSelfenrolUsersInCourse = $elearning->getGradeReportSelfEnrol(2, 12345);
print_r($elearningGetSelfenrolUsersInCourse);
die();