<?php
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Acceso no válido");
}

$tipo = $_POST['tipo'];
$cantidad = (int) $_POST['cantidad'];

if ($cantidad <= 0) {
    die("Cantidad inválida");
}

/* Ver cuántas están rentadas */
$sql = "
    SELECT id_bicicleta 
    FROM bicicleta 
    WHERE tipo = ? AND disponibilidad = 0 
    LIMIT $cantidad
";
$stmt = $conexion->prepare($sql);
$stmt->execute([$tipo]);
$bicis = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($bicis) < $cantidad) {
    die("No hay suficientes bicicletas rentadas de ese modelo");
}

/* Devolver bicicletas */
$ids = array_column($bicis, 'id_bicicleta');
$placeholders = implode(',', array_fill(0, count($ids), '?'));

$sqlUpdate = "
    UPDATE bicicleta 
    SET disponibilidad = 1,
        estado_mantenimiento = 0,
        fecha_disponible = NULL
    WHERE id_bicicleta IN ($placeholders)
";

$stmt = $conexion->prepare($sqlUpdate);
$stmt->execute($ids);

header("Location: ../menu.php?msg=devueltas");
exit;
