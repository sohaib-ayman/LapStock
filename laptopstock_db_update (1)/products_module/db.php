<?php

$local = "localhost";
$user = "root";
$pass = "";
$db = "system1";

$con = mysqli_connect($local, $user, $pass, $db);

if (!$con) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8mb4");

?>