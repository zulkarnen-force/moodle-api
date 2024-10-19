<?php
namespace Zulkarnen;
use MoodleRest;
class MoodleApi
{
     private $MoodleRest;

     public function __construct($token, $serverAddress)
     {
          $this->MoodleRest = new MoodleRest();
          $this->MoodleRest->setServerAddress($serverAddress);
          $this->MoodleRest->setToken($token);
          $this->MoodleRest->setReturnFormat(MoodleRest::RETURN_JSON);
          $this->MoodleRest->setDebug();
     }

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

     public function getGradeReport($courseId)
     {
          $params = ['courseid' => $courseId];
          return $this->MoodleRest->request('gradereport_user_get_grade_items', $params);
     }

     public function registerUser($userData)
     {
          $params = [
               'users' => [$userData]
          ];
          return $this->MoodleRest->request('core_user_create_users', $params);
     }
}


