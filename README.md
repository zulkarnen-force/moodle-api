# Moodle API PHP Library

## Overview

The **Moodle API PHP Library** is a simple PHP library built to interact with the Moodle REST API, providing functions to fetch user details, manage user accounts, retrieve course grades, and more. It is designed to easily integrate into PHP applications like CodeIgniter, Laravel, or any plain PHP project.

This library relies on the [llagerlof/moodlerest](https://github.com/llagerlof/MoodleRest) package to communicate with Moodle's Web Services API.


## Installation

### Via Composer

First, make sure Composer is installed. Then, add the library to your project:

```bash
composer require zulkarnen/moodle-api
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

```php
$courseId = 2; // Your Moodle course ID
$gradeReport = $moodleApi->getGradesReport($courseId);
print_r($gradeReport);
```

## License

This project is licensed under the MIT License. See the [LICENSE](./LICENSE) file for more information.


## Contributing

Feel free to contribute to this project by submitting issues or pull requests.

