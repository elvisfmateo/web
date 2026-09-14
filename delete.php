<?php

require_once "db.php";

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index.html");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET["id"]);
$user_id = $_SESSION["user_id"];

$sql = "DELETE FROM Tareas
        WHERE id = ?
        AND user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $user_id);

$stmt->execute();

header("Location: index.php");
exit();

?>
