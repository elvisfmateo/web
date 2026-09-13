<?php

require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.html");
    exit();
}

$username = trim($_POST["username"]);
$password = $_POST["password"];

if (empty($username) || empty($password)) {
    die("Todos los campos son obligatorios.");
}

if (strlen($password) < 6) {
    die("La contrasena debe tener al menos 6 caracteres.");
}

$sql = "SELECT id FROM Usuarios WHERE Username = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    die("El usuario ya existe.");
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO Usuarios (Username, Password)
        VALUES (?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password_hash);

if ($stmt->execute()) {

    header("Location: login.html");
    exit();

} else {

    die("Error al registrar el usuario: " . $conn->error);
}

?>