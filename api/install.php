<?php

echo 'Your PHP version is '.PHP_VERSION.'<br>';
$php_min_version = '5.4.0';
if (version_compare(phpversion(), $php_min_version, '<=')) {
    echo 'Minimal required PHP version is '.$php_min_version;
    echo 'Please upgrade your PHP';
    die;
}


include '../settings/settings.php';

error_reporting(E_ALL ^ E_DEPRECATED);

$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$link) {
    echo mysqli_connect_error();
    die;
}


$query = '
DROP TABLE IF EXISTS `editors`
';
mysqli_query($link, $query);

$query = '
CREATE TABLE `editors` (
  `id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`email`)
) DEFAULT CHARSET = utf8 ENGINE = InnoDB
';
mysqli_query($link, $query);

$query = '
INSERT INTO `editors` (`email`) VALUES ("'. mysqli_real_escape_string($link, $admin_email) .'")
';
mysqli_query($link, $query);


if (!mysqli_errno($link)) {
    //rename('install.php', '../settings/install.php');
?>
    <br>
    <br>
    <h2>Database tables successfully (re)created</h2>
    <br>
    <h3>Script has Moved install.php to "settings" folder !!!</h3>
    <br>
    <br>
    And do check if <b>error_reporting</b> is set to <b>0</b> on your production environment...
<?php
}
else {
    echo mysqli_error($link);
}