<?php
require_once "../config/database.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];

    $sql = "SELECT id_usuario FROM usuario WHERE nombre_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expiracion = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $sql = "INSERT INTO recuperacion_contrasena (id_usuario, token, expiracion)
                VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$user["id_usuario"], $token, $expiracion]);

        // Simulación de correo
        $msg = "Enlace de recuperación:<br>
        <a href='reset_password.php?token=$token'>
            Recuperar contraseña
        </a>";
    } else {
        $msg = "El correo no está registrado";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="form-wrapper">
    <div class="form-side">
        <form class="my-form" method="POST">
            <h1>Recuperar contraseña 🔐</h1>

            <div class="text-field">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <button class="my-form__button">Enviar</button>

            <p style="text-align:center; margin-top:10px;">
                <?= $msg ?>
            </p>

            <div class="my-form__actions">
                <a href="login.php">Volver al login</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
