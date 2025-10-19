# **Moodle API PHP Library**

## **Requirement**

* [Elearning Plugin](https://github.com/zulkarnen-force/Moodle-Plugin)

## **Overview**

The **Moodle API PHP Library** is a simple PHP library built to interact with the Moodle REST API, providing functions to fetch user details, manage user accounts, retrieve course grades, and more. It is designed to easily integrate into PHP applications like CodeIgniter, Laravel, or any plain PHP project.

This library relies on the llagerlof/moodlerest package to communicate with Moodle's Web Services API.

## **Installation**

### **Via Composer**

First, make sure Composer is installed. Then, add the library to your project:

```php

composer require zulkarnen/moodleapi
```

## **Usage**

### **Initializing the Library**

To use this library, you need to instantiate the MoodleApi class with your Moodle API token and server address.

```php

use Zulkarnen\MoodleApi;

$token = 'your_moodle_api_token';
$moodleBaseUrl = 'http://your-moodle-site.com';
$moodleApi = new MoodleApi($token, $moodleBaseUrl);
```

### **Authentication (Getting the Token)**

While the new usage example shows fetching the token via a separate GetToken class (which uses Zulkarnen\\MoodleAuth), the MoodleApi class itself provides a convenience method for obtaining the token using Moodle credentials:

```php

<?php
require_once "vendor/autoload.php"; // Make sure you include the Composer autoload file
use Zulkarnen\MoodleAuth;

// If MoodleApi has an internal token acquisition method
$token = MoodleAuth::fetchToken($baseUrl, $username, $password, $serviceName);

// Expected success response (token string inside an array)
/*
Array
(
    [token] => 7f28e0c47e8092508432f382f8806954
)
*/
```

### **Example: 1\. Get User Courses**

This function retrieves the list of courses a specific user is enrolled in. It relies on the separate GetToken class to handle authentication and configuration.

```php

<?php

//step 1: include the composer autoload file
require_once "vendor/autoload.php";

use Zulkarnen\MoodleApi;

function getMyCourses(){    
    $serverAddress = 'https://yourmoodlesite.com';
    $token = 'your_token';
    $username = 'username';

    $moodleApi = new MoodleApi($token, $serverAddress);

    // 1. Get user data to find the user ID
    $userData = $moodleApi->getUserByUsername($username);

    // Assuming getUserByUsername returns an array of users, and we take the first one's ID
    $userId = $userData[0]['id'] ?? null;
    if (!$userId) {
        // Handle error: user not found
        header('Content-Type: application/json');
        echo json_encode(['error' => 'User not found.']);
        return;
    }

    // 2. Fetch courses for the user
    $userCourses = $moodleApi->getUserCourses($userId);

    header('Content-Type: application/json');
    echo json_encode(
        $userCourses
    );
}

getMyCourses();
?>
```

### **Example: 2\. Get User Grades in a Course**

This function demonstrates how to retrieve the full grade report for the currently authenticated user within a specific Moodle course.

```php

<?php

//step 1: include the composer autoload file
require_once "vendor/autoload.php";

use Zulkarnen\MoodleApi;

// get grades report by course id
function getUserGrades() {
    // Note: Course ID 1 is typically the default "Site Home" course.
    $courseId = $_GET['courseid'] ?? 1; 
    
   
    $serverAddress = 'moodle_site';
    $token = 'your_token';  

    // Create MoodleApi instance
    $moodleApi = new MoodleApi(
        $token,
        $serverAddress
    );

    // Fetch grades report using the dedicated method
    header('Content-Type: application/json');
    echo json_encode($moodleApi->getUserGrades($courseId));
   
}   

getUserGrades();
?>

```

### **Create Grade Category**

```php

// Instantiate the API client with the Moodle API token and server address.
$moodleApi = new MoodleApi('your-api-token', '[https://moodle.example.com/webservice/rest/server.php](https://moodle.example.com/webservice/rest/server.php)');

// Required parameters
$courseId = 10;  // ID of the Moodle course
$categoryName = 'Assignment Grades';  // Name of the new grade category

// Optional settings for the grade category
$options = [
    'aggregation' => 10,        // Simple weighted mean of grades
    'gradetype' => 1,           // Value-based grading
    'grademax' => 100,          // Maximum grade
    'grademin' => 0,            // Minimum grade
    'gradepass' => 50,          // Grade to pass
    'parentcategoryid' => 5,    // If this is a subcategory
];

// Create a new grade category
$response = $moodleApi->createGradeCategory($courseId, $categoryName, $options);

```

### **Assign Student with Selfenrol in a Course**

Function createStudentSelfEnrollToCourse is used to assign student with selfenrol in a course.

```php

$courseId = 2;
$userId = 2;
$enrolmentKey = "TAHUN_2024";

$result = $moodleApi->createStudentSelfEnrollToCourse(
    $userId,
    $courseId,
    $enrolmentKey
);
print_r($result);

/*
(
   [status] => success
   [message] => User has been successfully enrolled in the course.
)
*/

```

### **Get Selfenrol in a Course**

```php

$result = $moodleApi->getSelfEnrolCourse(2, 12345);
print_r($result);

/*
Array
(
    [course] => Array
        (
            [id] => 2
            [fullname] => Pengembangan Web
            [shortname] => PW
            [category] => 1
        )

    [selfenrol] => Array
        (
            [name] => TAHUN_2024
            [password] => 12345
        )

    [users] => Array
        (
            [0] => Array
                (
                    [id] => 7
                    [username] => zulkarnen
                    [userfullname] => Zul Karnen
                    [email] => zulkarnen@example.com
                )

        )

)
*/

```

## **Available Methods**

This is a list of high-level wrapper methods available in the Zulkarnen\\MoodleApi library:

| Method | Parameters | Description |
| :---- | :---- | :---- |
| getToken | ($username, $password, $serviceName) | Get token using username, password and service name. |
| getUser | ($key, $value) | Get user by criteria (e.g., id, email). |
| getUserByUsername | ($username) | Get user by username (convenience wrapper for getUser). |
| getUserCourses | ($userId) | Retrieves the list of courses a specific user is enrolled in. |
| getCourseGradeCategories | ($courseId) | Get course grade categories. |
| getGradesReport | ($courseId) | Get the full grade report for a course (might require user context). |
| **getUserGrades** | **($courseId)** | **NEW\!** Get the authenticated user's grades within a specific course. |
| getGradeReportSelfEnrol | ($courseId, $enrolmentKey) | Get grade report with selfenrol in course. |
| getSelfEnrolCourse | ($courseId, $enrolmentKey) | Get users with selfenrol in course. |
| createStudentSelfEnrollToCourse | ($userId, $courseId, $enrolmentKey) | Create student self-enroll to course. |

## **License**

This project is licensed under the MIT License. See the LICENSE file for more information.

## **Contributing**

Feel free to contribute to this project by submitting issues or pull requests.
