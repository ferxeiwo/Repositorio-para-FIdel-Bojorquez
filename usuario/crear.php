<?php
require_once "../config/database.php";
require_once "../session/validar.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["usuario"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuario (nombre_usuario, contrasena) VALUES (?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$usuario, $password]);

    $msg = "Usuario creado correctamente";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Crear usuario</title>
</head>
<body>
<h1>Crear usuario</h1>

<form method="POST">
    <input type="text" name="usuario" placeholder="Usuario" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button>Guardar</button>
</form>

<p><?= $msg ?></p>
<a href="../index.php">Volver</a>
</body>
</html>
