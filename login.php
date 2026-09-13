<?php

require_once "db.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.html");
    exit();
}

$username = trim($_POST["username"]);
$password = $_POST["password"];

if (empty($username) || empty($password)) {
    die("Usuario y contrasena son obligatorios.");
}

/* Buscar usuario */
$sql = "SELECT id, Username, Password
        FROM Usuarios
        WHERE Username = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Usuario o contrasena incorrectos.");
}

$user = $result->fetch_assoc();

/* Verificar contrasena */
if (!password_verify($password, $user["Password"])) {
    die("Usuario o contrasena incorrectos.");
}

/* Crear sesion segura */
session_regenerate_id(true);

$_SESSION["user_id"] = $user["id"];
$_SESSION["username"] = $user["Username"];

/* Recordarme */
if (isset($_POST["remember"])) {

    $token = bin2hex(random_bytes(32));

    $token_hash = hash("sha256", $token);

    /*
     * IMPORTANTE:
     * Para usar remember_token necesitamos una columna adicional.
     */
    $sql = "UPDATE Usuarios
            SET remember_token = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $token_hash, $user["id"]);
    $stmt->execute();

    setcookie(
        "remember_token",
        $token,
        [
            "expires" => time() + (30 * 24 * 60 * 60),
            "path" => "/",
            "secure" => isset($_SERVER["HTTPS"]),
            "httponly" => true,
            "samesite" => "Lax"
        ]
    );
}

header("Location: index.php");
exit();

?>