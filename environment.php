<?php
require 'vendor/autoload.php';
require 'credentials.php';

Mysqli2::isDev(true);

// Mysqli2 v2: connect failure throws DatabaseException, no connect_errno check needed
$mysqli = Mysqli2::getInstance($mysql_host, $mysql_port, $mysql_user, $mysql_password, $mysql_database);
$mysqli->set_charset("utf8");
