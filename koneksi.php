<?php
$server = "sql12.freesqldatabase.com";
$user = "sql12771618";
$password = "wxcKBBJ9XR";
$database = "sql12771618";
$port = 3306;

$db = mysqli_connect($server, $user, $password, $database, $port);
mysqli_query($db, "SET time_zone = '+07:00'");

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
