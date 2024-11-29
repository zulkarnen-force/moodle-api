# Moodle API PHP Library

## Requirement

- [Elearning Plugin](https://github.com/zulkarnen-force/Moodle-Plugin)

## Overview

The **Moodle API PHP Library** is a simple PHP library built to interact with the Moodle REST API, providing functions to fetch user details, manage user accounts, retrieve course grades, and more. It is designed to easily integrate into PHP applications like CodeIgniter, Laravel, or any plain PHP project.

This library relies on the [llagerlof/moodlerest](https://github.com/llagerlof/MoodleRest) package to communicate with Moodle's Web Services API.

## Installation

### Via Composer

First, make sure Composer is installed. Then, add the library to your project:

```bash
composer require zulkarnen/moodleapi
```

## Usage

### Initializing the Library

To use this library, you need to instantiate the `MoodleApi` class with your Moodle API token and server address.

```php
use Zulkarnen\MoodleApi;

$token = 'your_moodle_api_token';
$serverAddress = 'http://your-moodle-site.com/webservice/rest/server.php';

$moodleApi = new MoodleApi($token, $serverAddress);
```

### Example Usage

#### Get Grade Report

```php
$courseId = 2; // Your Moodle course ID
$gradeReport = $moodleApi->getGradesReport($courseId);
print_r($gradeReport);
```

### Create Grade Category

```php

// Instantiate the API client with the Moodle API token and server address.
$moodleApi = new MoodleApi('your-api-token', 'https://moodle.example.com');

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

### Assign Student with Selfenrol in a Course

Function `createStudentSelfEnrollToCourse` is used to assign student with selfenrol in a course.

```php
$courseId = 2;
$userId = 2;
$enrolmentKey = "TAHUN_2024";
$createStudentSelfEnrollToCourse = $elearning->createStudentSelfEnrollToCourse(
    2,
    2,
    "TAHUN_2024"
);
print_r($result);
```

```
(
   [status] => success
   [message] => User has been successfully enrolled in the course.
)
```

### Get Selfenrol in a Course

```php
$result = $moodleApi->elearningGetSelfenrolUsersInCourse(2, 12345);
print_r($result);
```

```
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
```

## Available Methods

- `getUser($key, $value)` - Get user by criteria.
- `getUserByUsername($username)` - Get user by username.
- `getCourseGradeCategories($courseId)` - Get course grade categories.
- `getGradeReportCourse($courseId)` - Get grade report in course.
- `getGradeReportSelfEnrol($courseId, $enrolmentKey)` - Get grade report with selfenrol in course.
- `getSelfEnrolCourse($courseId, $enrolmentKey)` - Get users with selfenrol in course.
- `createStudentSelfEnrollToCourse($userId, $courseId, $enrolmentKey)` - Create student selfenroll to course.

## License

This project is licensed under the MIT License. See the [LICENSE](./LICENSE) file for more information.

## Contributing

Feel free to contribute to this project by submitting issues or pull requests.
