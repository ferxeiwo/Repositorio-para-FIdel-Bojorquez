<?php
require_once "../config/database.php";
require_once "../session/validar.php";

$id = $_GET["id"] ?? null;
$msg = "";

// Obtener usuario
$sql = "SELECT * FROM usuario WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Usuario no encontrado");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["usuario"];

    if (!empty($_POST["password"])) {
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $sql = "UPDATE usuario SET nombre_usuario = ?, contrasena = ? WHERE id_usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$usuario, $password, $id]);
    } else {
        $sql = "UPDATE usuario SET nombre_usuario = ? WHERE id_usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$usuario, $id]);
    }

    $msg = "Usuario actualizado";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar usuario</title>
</head>
<body>
<h1>Editar usuario</h1>

<form method="POST">
    <input type="text" name="usuario" value="<?= $user["nombre_usuario"] ?>" required>
    <input type="password" name="password" placeholder="Nueva contraseña (opcional)">
    <button>Actualizar</button>
</form>

<p><?= $msg ?></p>
<a href="../index.php">Volver</a>
</body>
</html>
