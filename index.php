<?php

require_once "db.php";

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT id, tarea
        FROM Tareas
        WHERE user_id = ?
        ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Mi To Do List</title>

    <link rel="stylesheet" href="styles.css">

</head>

<body>

<div class="container">

    <h1>Mi To Do List</h1>

    <p class="welcome">

        Bienvenido,

        <strong>
            <?php
            echo htmlspecialchars($_SESSION["username"]);
            ?>
        </strong>

        <a href="logout.php" class="logout">
            Salir
        </a>

    </p>

    <form action="add.php" method="POST">

        <input
            type="text"
            name="tarea"
            placeholder="Escribe una nueva tarea..."
            maxlength="255"
            required
        >

        <button type="submit">
            Agregar
        </button>

    </form>

    <ul>

        <?php while ($row = $result->fetch_assoc()): ?>

            <li>

                <span>
                    <?php
                    echo htmlspecialchars($row["tarea"]);
                    ?>
                </span>

                <a
                    href="delete.php?id=<?php echo $row["id"]; ?>"
                    class="delete"
                    onclick="return confirm('¿Eliminar esta tarea?');"
                >
                    ❌
                </a>

            </li>

        <?php endwhile; ?>

    </ul>

</div>

</body>

</html>