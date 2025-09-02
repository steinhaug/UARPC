<?php
require 'vendor/autoload.php';
require 'credentials.php';

Mysqli2::isDev(true);

$mysqli = Mysqli2::getInstance($mysql_host, $mysql_port, $mysql_user, $mysql_password, $mysql_database);
$mysqli->set_charset("utf8");
if ($mysqli->connect_errno) {
    echo 'Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error; 
    exit();
}
