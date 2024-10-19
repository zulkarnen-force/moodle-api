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
      * Get course user profiles for a list of users.
      * 
      * @return array Response from the Moodle API
      * 
      * @example
      * $profiles = $moodleApi->get_core_user_get_course_user_profiles();
      * print_r($profiles);
      */
     public function get_core_user_get_course_user_profiles()
     {
          $params = [
               'userlist' => [
                    ['userid' => 5, 'courseid' => 2],
                    ['userid' => 4, 'courseid' => 2]
               ]
          ];
          return $this->MoodleRest->request('core_user_get_course_user_profiles', $params);
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
     public function getGradeReport($courseId)
     {
          $params = ['courseid' => $courseId];
          return $this->MoodleRest->request('gradereport_user_get_grade_items', $params);
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
}
