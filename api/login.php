<?php

    // log in

    if (isset($_GET['authToken']) && session_status() == PHP_SESSION_NONE) {   // don't allow log in for the second time
        include "../settings/settings.php";

        $url = 'https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=' . urlencode($_GET['authToken']);
        $json = @file_get_contents($url);
        $obj = @json_decode($json);
        if ($obj && isset($obj->aud) && $obj->aud === $google_api_id) {

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


            // auth
            if ($_SESSION['email'] !== $admin_email) {
                $result = mysqli_query($link, 'SELECT * FROM `users` WHERE email="' . mysqli_real_escape_string($link, $obj->email) . '"');
                if (!$result) {
                    if ($s = mysqli_error($link)) {
                        http_response_code(500);
                        if ($error_reporting_level !== 0) {
                            echo $s;
                        }
                        die;
                    }

                    http_response_code(401);
                    die;
                }
            }


            session_start();
            $_SESSION['userGoogleId'] = $obj->sub;
            $_SESSION['email'] = $obj->email;
            $_SESSION['xsrfToken'] = base64_encode(openssl_random_pseudo_bytes(32));


            echo $_SESSION['xsrfToken'];
        }
        else {
            http_response_code(500);
            echo 'Google authentication internal error?';
        }
    }


    // log out is xsrf safe

    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        session_start();

        $post = json_decode(file_get_contents('php://input'), true);

        if (isset($post['logout']) && isset($_SESSION['xsrfToken']) && $post['logout'] === $_SESSION['xsrfToken']) {
            session_destroy();
        }
        else {
            // todo
        }
    }
