<?php

namespace Zulkarnen;

use MoodleRest;

class MoodleApi
{
     private $MoodleRest;

     /**
      * MoodleApi constructor.
      * 
      * @param string $token Moodle API token
      * @param string $serverAddress Moodle server address
      */
     public function __construct($token, $serverAddress)
     {
          $this->MoodleRest = new MoodleRest();
          $this->MoodleRest->setServerAddress($serverAddress);
          $this->MoodleRest->setToken($token);
          $this->MoodleRest->setReturnFormat(MoodleRest::RETURN_ARRAY);
     }


     /**
      * Get grade report for a course.
      * 
      * @param int $courseId Course ID
      * @return array Response from the Moodle API
      * 
      * @example
      * $gradeReport = $moodleApi->getGradeReport(2);
      * print_r($gradeReport);
      */
     public function getGradesReport($courseId)
     {
          $params = ['courseid' => $courseId];
          return $this->MoodleRest->request('gradereport_user_get_grade_items', $params)['usergrades'];
     }

     /**
      * Get grade categories for a course.
      * 
      * @param int $courseId Course ID
      * @return array Response from the Moodle API
      * 
      * @example
      * $gradeCategories = $moodleApi->getGradeCategories(2);
      * print_r($gradeCategories);
      */
     public function getGradeCategories($courseId)
     {
          $params = [
               'courseid' => $courseId
          ];
          return $this->MoodleRest->request('core_grades_get_grades', $params);
     }

     /**
      * Register a new user on Moodle.
      * 
      * @param array $userData User data to register
      * @return array Response from the Moodle API
      * 
      * @example
      * $userData = [
      *     'username' => 'johndoe',
      *     'password' => 'Test@12345',
      *     'firstname' => 'John',
      *     'lastname' => 'Doe',
      *     'email' => 'johndoe@example.com',
      *     'auth' => 'manual',
      *     'idnumber' => '123456',
      *     'lang' => 'en',
      *     'timezone' => 'Asia/Jakarta',
      * ];
      * $response = $moodleApi->registerUser($userData);
      * print_r($response);
      */
     public function registerUser($userData)
     {
          $params = [
               'users' => [$userData]
          ];
          return $this->MoodleRest->request('core_user_create_users', $params);
     }

     /**
      * Get all users by their usernames.
      * 
      * @param array $userIds Usernames to filter by
      * @return array Response from the Moodle API
      * 
      * @example
      * $usernames = ['johndoe', 'janedoe'];
      * $users = $moodleApi->getAllUserByIds($usernames);
      * print_r($users);
      */
     public function getAllUserByIds($userIds = [])
     {
          $params = [
               'criteria' => [
                    [
                         'key' => 'username',
                         'value' => implode(',', $userIds) // Optional: filter by user IDs if provided
                    ]
               ]
          ];
          return $this->MoodleRest->request('core_user_get_users', $params);
     }

     /**
      * Get a user by their username.
      * 
      * @param string $username Username of the user
      * @return array Response from the Moodle API
      * 
      * @example
      * $user = $moodleApi->getUserByUsername('johndoe');
      * print_r($user);
      */
     public function getUserByUsername($username)
     {
          $params = [
               'criteria' => [
                    [
                         'key' => 'username',
                         'value' => $username
                    ]
               ]
          ];
          return $this->MoodleRest->request('core_user_get_users', $params);
     }

     /**
      * Get a user by a specific key (e.g., username or email).
      * 
      * @param string $key The key to filter by (e.g., 'username' or 'email')
      * @param string $value The value of the key
      * @return array|null Response from the Moodle API or null if no user found
      * 
      * @example
      * $user = $moodleApi->getUser('email', 'johndoe@example.com');
      * print_r($user);
      */
     public function getUser($key, $value)
     {
          $params = [
               'criteria' => [
                    [
                         'key' => $key,
                         'value' => $value
                    ]
               ]
          ];
          $response = $this->MoodleRest->request('core_user_get_users', $params);
          return isset($response['users'][0]) ? $response['users'][0] : null; // Return user if found or null
     }

     /**
      * Get students enrolled in a specific course by course ID.
      * 
      * @param int $courseId Course ID
      * @return array Response from the Moodle API
      * 
      * @example
      * $students = $moodleApi->getStudentsInCourse(2);
      * print_r($students);
      */
     public function getStudentsInCourse($courseId)
     {
          $params = ['courseid' => $courseId];
          return $this->MoodleRest->request('core_enrol_get_enrolled_users', $params);
     }


     /**
      * Get all courses.
      * 
      * @return array Response from the Moodle API
      * 
      * @example
      * $courses = $moodleApi->getCourses();
      * print_r($courses);
      */
     public function getCourses()
     {
          return $this->MoodleRest->request('core_course_get_courses');
     }

     /**
      * Get a course by its ID.
      * 
      * @param int $courseId Course ID
      * @return array Response from the Moodle API
      * 
      * @example
      * $course = $moodleApi->getCourse(2);
      * print_r($course);
      */
     public function getCourse($courseId)
     {
          try {
               //code...
               $params = [
                    'field' => 'id',
                    'value' => $courseId,
               ];
               return $this->MoodleRest->request('core_course_get_courses_by_field', $params)['courses'][0];
          } catch (\Throwable $th) {
               throw $th;
          }
     }

     /**
      * Get all categories.
      * 
      * @return array Response from the Moodle API
      * 
      * @example
      * $categories = $moodleApi->getCategories();
      * print_r($categories);
      */
     public function getCategories()
     {
          return $this->MoodleRest->request('core_category_get_categories');
     }


     /**
      * Add a student to a course.
      * 
      * @param int $courseId Course ID
      * @param int $studentId Student ID
      * @return array Response from the Moodle API
      * 
      * @example
      * $response = $moodleApi->addStudentToCourse(2, 3);
      * print_r($response);
      */
     public function addStudentToCourse($studentId, $courseId)
     {
          $params = [
               'enrolments' => [
                    [
                         'roleid' => 5, // Role ID for the student
                         'userid' => $studentId, // ID of the student to enroll
                         'courseid' => $courseId, // ID of the course where the student should be enrolled
                    ],
               ]
          ];
          return $this->MoodleRest->request('enrol_manual_enrol_users', $params);
     }
}
