<?php

require_once "db.php";

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$tarea = trim($_POST["tarea"]);
$user_id = $_SESSION["user_id"];

if (empty($tarea)) {
    header("Location: index.php");
    exit();
}

$sql = "INSERT INTO Tareas (tarea, user_id)
        VALUES (?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $tarea, $user_id);

$stmt->execute();

header("Location: index.php");
exit();

?>