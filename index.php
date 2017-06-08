<?php
include 'settings/settings.php';
session_start();
$isLogged = isset($_SESSION['email']);
if ($isLogged) {
    $email = $_SESSION['email'];
    $token = $_SESSION['xsrfToken'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ux</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <base href="/">

    <meta name="google-signin-client_id" content="<?php echo $google_api_id ?>">
    <?php if (!$isLogged) { ?>
        <style>
            #test3dPartyCookies {
                position: absolute;
                width: 436px;
                left: calc(50% - 220px);
                top: 24px;
                z-index: 1000;
                box-shadow: 0 0 50px darkred, 0 0 0 30px white; /* todo modal*/
                border-radius: 4px;
                border: 2px dotted darkred;
                padding: 16px;
                display: none;
                background: #ff9;
            }
            a[target="_blank"] {
                display: block;
                letter-spacing: 0.2px;
                margin-top: 10px;
                background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAVklEQVR4Xn3PgQkAMQhDUXfqTu7kTtkpd5RA8AInfArtQ2iRXFWT2QedAfttj2FsPIOE1eCOlEuoWWjgzYaB/IkeGOrxXhqB+uA9Bfcm0lAZuh+YIeAD+cAqSz4kCMUAAAAASUVORK5CYII=") right center no-repeat;
                text-decoration: none;
            }
        </style>
    <?php } ?>
</head>

<body>
<?php if ($isLogged) { ?>
    <script>
        var xsrfToken = '<?php echo $_SESSION['xsrfToken'] ?>';
    </script>

    <app-root><div id="loading">Loading...</div></app-root>

<?php } else { ?>

    <br>
    <br>
    <div class="g-signin2" data-onsuccess="onGLogIn" data-onfailure="onGLoginFailure"></div>
    <script src="https://apis.google.com/js/platform.js" async defer></script>

    <script>
        function onGLogIn(response) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', location.href + 'api/login.php?authToken=' + encodeURIComponent(response.getAuthResponse().id_token));
            xhr.onload = function() {
                if (xhr.status === 200) {
                    location.reload();
                }
                else {
                    alert('Could not log into ' + location.hostname + '. ' + xhr.responseText); // todo review this
                }
            };
            xhr.send();
        }


        function onGLoginFailure(data) {
            debugger;
            // todo alert!
        }


        // check for 3d party cookies are enabled
        window.addEventListener("message", function (evt) {
            if (evt.data === 'MM:3PCunsupported') {
                document.getElementById('test3dPartyCookies').style.display = 'block';
            }
        });
    </script>

    <div id="test3dPartyCookies"><b style="font-size: 1.3em;">Third party cookies are disabled in your browser</b><br><br>
        Sign in using Google won't work unless you enable this feature in browser settings<br>
        <a target=_blank href="https://www.google.com/search?q=how+do+I+enable+3rd+party+cookies+in+my+browser" style="font-size: 20px">Find solution in the internet (search using Google)</a>
    </div>
    <iframe src="//mindmup.github.io/3rdpartycookiecheck/start.html" style="display:none"></iframe>

<?php } ?>

<link href="dist/styles.bundle.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="dist/inline.bundle.js"></script>
<script src="dist/vendor.bundle.js"></script>
<script src="dist/main.bundle.js"></script>

</body>
</html>