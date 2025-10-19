<?php

namespace Zulkarnen;

use Exception;
use MoodleRest;

class MoodleApi
{
    public function __construct($token, $moodleUrl)
    {
        $serverAddress = $moodleUrl.'/webservice/rest/server.php';
        $this->serverAddress = $serverAddress;       
        $this->MoodleRest = new MoodleRest();
        $this->MoodleRest->setServerAddress($serverAddress);
        $this->MoodleRest->setToken($token);
        $this->MoodleRest->setReturnFormat(MoodleRest::RETURN_ARRAY);
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
            "field" => "username",
            "values" => [$username]
        ];
        $users = $this->MoodleRest->request("core_user_get_users_by_field", $params);
        return $users[0] ?? null;
    }

    public function getUserCourses($userId)
    {
        $params = [
            "userid" => $userId,
        ];
        return $this->MoodleRest->request("core_enrol_get_users_courses", $params);
    }   

    public function getUserGrades($courseId)
    {
        // Step 1: Get enrolled users and build a lookup map by userid
        $usersInCourse = $this->getEnrolledUsers($courseId);

        // check if usersInCourse is valid
        if (!is_array($usersInCourse)) {
            throw new Exception("Failed to fetch enrolled users for course ID: {$courseId}");
        }

        // Create an associative array: [userid => email]
        $userEmailMap = [];
        foreach ($usersInCourse as $user) {
            // Ensure the user object has 'id' and 'email'
            if (isset($user['id']) && isset($user['email'])) {
                $userEmailMap[$user['id']] = $user['email'];
            }
        }

        // Step 2: Get grade report
        $params = ['courseid' => $courseId];
        $gradesReport = $this->MoodleRest->request("gradereport_user_get_grade_items", $params);
        $usergrades = $gradesReport['usergrades'] ?? [];

        // Step 3: Inject email into each usergrade
        foreach ($usergrades as &$usergrade) {
            $userid = $usergrade['userid'] ?? null;
            $usergrade['email'] = $userid && isset($userEmailMap[$userid])
                ? $userEmailMap[$userid]
                : ''; // or null, depending on your preference
        }
        unset($usergrade); // break the reference

        // Step 4: Extract grade items (from first user, if available)
        $gradeItems = [];
        if (!empty($usergrades) && !empty($usergrades[0]['gradeitems'])) {
            foreach ($usergrades[0]['gradeitems'] as $item) {
                $gradeItems[] = [
                    'id' => $item['id'],
                    'itemtype' => $item['itemtype'],
                    'itemname' => $item['itemname']                   
                ];
            }
        }

        return [
            'gradeitems' => $gradeItems,
            'usergrades' => $usergrades
        ];
    }

    public function getEnrolledUsers($courseId)
    {
        $params = [
            'courseid' => $courseId            
        ];
        $enrolledUsers = $this->MoodleRest->request(
            "core_enrol_get_enrolled_users",
            $params
        );

        return $enrolledUsers;       
    }

    /**
     * Create a new grade category in a course.
     *
     * @param int $courseId The course ID where the category will be created
     * @param string $categoryName The name of the new grade category
     * @param int|null $parentCategoryId Optional, the parent category ID if this is a subcategory
     * @return array Response from the Moodle API
     */
    public function createGradeCategory($courseId, $categoryName, $options = [])
    {
        $params = [
            "courseid" => $courseId,
            "categories" => [
                [
                    "fullname" => $categoryName,
                    "options" => $options,
                ],
            ],
        ];
        $response = $this->MoodleRest->request(
            "core_grades_create_gradecategories",
            $params
        );
        return $response;
    }

    /**
     * Enroll a user in a course via self-enrollment.
     *
     * @param int $courseId The ID of the course to enroll the user in
     * @param int $userId The ID of the user to enroll
     * @param string $enrolmentKey Optional, the enrollment key for the course
     * @return array Response from the Moodle API
     */
    public function enrolSelfEnrolUser($courseId, $userId, $enrolmentKey = "")
    {
        $params = [
            "courseid" => $courseId,
            "userid" => $userId,
            "enrolpassword" => $enrolmentKey,
        ];

        $response = $this->MoodleRest->request(
            "enrol_self_enrol_user",
            $params
        );
        return $response;
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
            "users" => [$userData],
        ];
        return $this->MoodleRest->request("core_user_create_users", $params);
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
            "criteria" => [
                [
                    "key" => "username",
                    "value" => implode(",", $userIds), // Optional: filter by user IDs if provided
                ],
            ],
        ];
        return $this->MoodleRest->request("core_user_get_users", $params);
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
            "criteria" => [
                [
                    "key" => $key,
                    "value" => $value,
                ],
            ],
        ];
        $response = $this->MoodleRest->request("core_user_get_users", $params);
        return isset($response["users"][0]) ? $response["users"][0] : null; // Return user if found or null
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
        $params = ["courseid" => $courseId];
        return $this->MoodleRest->request(
            "core_enrol_get_enrolled_users",
            $params
        );
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
        return $this->MoodleRest->request("core_course_get_courses");
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
            $params = [
                "field" => "id",
                "value" => $courseId,
            ];
            return $this->MoodleRest->request(
                "core_course_get_courses_by_field",
                $params
            )["courses"][0];
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
        return $this->MoodleRest->request("core_category_get_categories");
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
            "enrolments" => [
                [
                    "roleid" => 5, // Role ID for the student
                    "userid" => $studentId, // ID of the student to enroll
                    "courseid" => $courseId, // ID of the course where the student should be enrolled
                ],
            ],
        ];
        return $this->MoodleRest->request("enrol_manual_enrol_users", $params);
    }

    public function getCourseCategories($categoryId)
    {
        $params = [
            "criteria" => [
                [
                    "key" => "idnumber",
                    "value" => $categoryId,
                ],
            ],
        ];
        return $this->MoodleRest->request(
            "core_course_get_categories",
            $params
        );
    }

    

    /**
     *
     * Fetches data from the Moodle API.
     *
     * This function interacts with the Moodle API to retrieve data related to courses and grade categories. The response is structured
     * as an associative array containing course details and grade categories.
     *
     * @param int $courseId Course ID
     * @return array Response from the Moodle API containing course and grade category data
     * @example
     * Example response:
     * Array
     * (
     *     [course] => Array
     *         (
     *             [id] => 2
     *             [fullname] => Pengembangan Web
     *             [shortname] => PW
     *             [category] => 1
     *         )
     *
     *     [gradecategories] => Array
     *         (
     *             [0] => Array
     *                 (
     *                     [id] => 3
     *                     [course_id] => 2
     *                     [name] => Sub-CPMK-1
     *                 )
     *         )
     *
     * )
     */

    public function getCourseGradeCategories(int $courseId)
    {
        $params = [
            "courseid" => $courseId,
        ];
        return $this->MoodleRest->request(
            "uad_get_gradecategories_course",
            $params
        );
    }

    /**
     *
     * Fetch grades for a course.
     *
     * @param int $courseId Course ID
     * @return array contains course and grade data
     * @example
     * Example response:
     * Array
     * (
     *     [course] => Array
     *         (
     *             [id] => 2
     *             [fullname] => Pengembangan Web
     *             [shortname] => PW
     *             [category] => 1
     *         )
     *
     *     [grades] => Array
     *         (
     *             [0] => Array
     *                 (
     *                     [id] => 3
     *                     [course_id] => 2
     *                     [name] => Sub-CPMK-1
     *                 )
     *         )
     *
     * )
     *
     *
     */

    public function getGradeReportCourse($courseId)
    {
        $params = [
            "courseid" => $courseId,
        ];
        return $this->MoodleRest->request("uad_get_gradereport", $params);
    }

    public function getGradeReportSelfEnrol($courseId, $password)
    {
        $params = [
            "courseid" => $courseId,
            "password" => $password,
        ];
        return $this->MoodleRest->request(
            "uad_get_gradereport_selfenrol",
            $params
        );
    }

    /**
     * Fetch users self enrol in a course.
     *
     * @param int $courseId Course ID
     * @return array contains course and grade data
     */
    public function getSelfEnrolCourse(int $courseId, string $password): mixed
    {
        $params = [
            "courseid" => $courseId,
            "password" => $password,
        ];
        return $this->MoodleRest->request("uad_get_selfenrol", $params);
    }

    /**
     * Create a self enrolment for a user in a course.
     *
     * @param int $userId User ID
     * @param int $courseId Course ID
     * @param string $password Password
     * @return array Response from the Moodle API containing the status and message
     * @example Example response: Array ( [status] => success [message] => User has been successfully enrolled in the course. )
     *
     */
    public function createStudentSelfEnrollToCourse(
        $userId,
        $courseId,
        $password
    ): mixed {
        $params = [
            "courseid" => $courseId,
            "password" => $password,
            "userid" => $userId,
        ];
        return $this->MoodleRest->request("uad_create_selfenrol", $params);
    }
}
