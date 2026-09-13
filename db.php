<?php

$host = "localhost";
$user = "root";
$pass = "Em201724.";
$db = "todo_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexion: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>