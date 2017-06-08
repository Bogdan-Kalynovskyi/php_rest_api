<?php

// we meet CORS when developing Angular with PHP, because they run on different servers
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, HEAD, DELETE, OPTIONS");
    }

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }

    exit(0);
}



// auth
session_start();

// anti xsrf
//if (isset($_SERVER['HTTP_AUTHORIZATION']) && isset($_SESSION['xsrfToken']) && $_SERVER['HTTP_AUTHORIZATION'] === $_SESSION['xsrfToken']) {
//    session_destroy();
//    http_response_code(401);
//    die;
//}

// session-based authorisation
if (!isset($_SESSION['email'])) {
    session_destroy();
    http_response_code(401);
    die;
}


// settings
include '../settings/settings.php';

// connect to the mysql database
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$link) {
    http_response_code(500);
    if ($error_reporting_level !== 0) {
        echo mysqli_connect_error();
    }
    die;
}

mysqli_set_charset($link, 'utf8');



function escape($value) {
    global $link;
    return '"' . mysqli_real_escape_string($link, $value) . '"';
}


if ($_SESSION['email'] !== $admin_email) {
// auth
    $result = mysqli_query($link, 'SELECT * FROM `users` WHERE email=' . escape($_SESSION['email']));
    if (!$result) {
        session_destroy();
        http_response_code(401);
        die;
    }
}


// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', substr($_SERVER['REQUEST_URI'], strlen($_SERVER['SCRIPT_NAME']) + 1));
$table = $request[0];
if ($method === 'GET' || $method === 'DELETE') {
    if (isset($request[1])) {
        $key = $request[1];
    } else {
        if ($method === 'DELETE') {
            http_response_code(400);
            die;
        }
        $key = null;
    }
}

if ($method === 'PUT' || $method === 'POST') {
    $post_payload = json_decode(file_get_contents('php://input'), true);

    $set = '';
    $first = true;
    foreach ($post_payload as $column => $value) {
        if ($first) {
            $first = false;
        } else {
            $set .= ',';
        }
        $set .= '`' . preg_replace('/[^a-z0-9_]+/i', '', $column) . '`=' . escape($value);
    }
}

// create SQL based on HTTP method
switch ($method) {
    case 'GET':
        $sql = "select * from `$table`" . ($key ? " WHERE id=$key" : '');
        break;
    case 'PUT':
        $sql = "update `$table` set $set where id=$key";
        break;
    case 'POST':
        $sql = "insert into `$table` set $set";
        break;
    case 'DELETE':
        $sql = "delete `$table` where id=$key";
        break;
}

// execute SQL statement
$result = mysqli_query($link, $sql);

if ($s = mysqli_error($link)) {
    http_response_code(400);
    if ($error_reporting_level !== 0) {
        echo $s;
    }
    die;
}

// die if SQL statement failed
if (!$result) {
    http_response_code(404);
    die;
}

// print results, insert id or affected row count
if ($method === 'GET') {
    header('Content-Type: application/json');

    if (!$key) echo '[';
    $i = 0;
    $n = mysqli_num_rows($result);
    for (; $i < $n; $i++) {
        echo ($i > 0 ? ',' : '') . json_encode(mysqli_fetch_object($result));   //
    }
    if (!$key) echo ']';
}
elseif ($method === 'POST') {
    echo mysqli_insert_id($link);
}
else {
    echo mysqli_affected_rows($link);
}
