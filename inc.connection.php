<?php
$servername = "192.168.35.161";
$database   = "wredpine";
$username   = "test";
$password   = "123123";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}