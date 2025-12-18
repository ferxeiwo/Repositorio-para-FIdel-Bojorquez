<?php
require_once "../config/database.php";

$token = $_GET["token"] ?? "";
$error = "";
$success = "";

// Verificar token
$sql = "SELECT * FROM recuperacion_contrasena 
        WHERE token = ? AND expiracion > NOW()";
$stmt = $conexion->prepare($sql);
$stmt->execute([$token]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Token inválido o expirado");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST["password"];
    $confirm = $_POST["confirm_password"];

    if ($password !== $confirm) {
        $error = "Las contraseñas no coinciden";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE usuario SET contrasena = ? WHERE id_usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$hash, $data["id_usuario"]]);

        // Eliminar token
        $sql = "DELETE FROM recuperacion_contrasena WHERE token = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$token]);

        $success = "Contraseña actualizada correctamente";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nueva contraseña</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="form-wrapper">
    <div class="form-side">
        <form class="my-form" method="POST">
            <h1>Nueva contraseña 🔑</h1>

            <?php if ($error): ?>
                <p style="color:red"><?= $error ?></p>
            <?php endif; ?>

            <?php if ($success): ?>
                <p style="color:green"><?= $success ?></p>
                <a href="login.php">Iniciar sesión</a>
            <?php endif; ?>

            <div class="text-field">
                <input type="password" name="password" placeholder="Nueva contraseña" required>
            </div>

            <div class="text-field">
                <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
            </div>

            <button class="my-form__button">Actualizar</button>
        </form>
    </div>
</div>
</body>
</html>
