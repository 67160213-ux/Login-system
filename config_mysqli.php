<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$DB_HOST = 'localhost';
$DB_USER = 's67160213';
$DB_PASS = 'SCXSEREf';
$DB_NAME = 's67160213';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

$mysqli->set_charset('utf8mb4');
?>


