<?php
require_once "../config/database.php";
require_once "../session/validar.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    die("ID no válido");
}

$sql = "DELETE FROM usuario WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->execute([$id]);

header("Location: ../index.php");
exit;
