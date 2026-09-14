<?php

require_once "db.php";

session_start();

/* Eliminar token */
if (isset($_SESSION["user_id"])) {

    $user_id = $_SESSION["user_id"];

    $sql = "UPDATE Usuarios
            SET remember_token = NULL
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}

/* Eliminar cookie */
setcookie(
    "remember_token",
    "",
    time() - 3600,
    "/"
);

/* Destruir sesion */
$_SESSION = [];

session_destroy();

header("Location: index.html");
exit();

?>
