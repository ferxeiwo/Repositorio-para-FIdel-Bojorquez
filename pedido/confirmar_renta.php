<?php
require_once "../config/database.php";

if (
    !isset($_GET['tipo'], $_GET['cantidad'], $_GET['dias'])
) {
    die("Datos incompletos");
}

$tipo = $_GET['tipo'];
$cantidad = (int) $_GET['cantidad'];
$dias = (int) $_GET['dias'];

if ($cantidad <= 0 || $dias <= 0) {
    die("Valores no válidos");
}

/* Obtener bicicletas disponibles del tipo */
$sql = "
    SELECT id_bicicleta, precio_renta 
    FROM bicicleta 
    WHERE tipo = ? AND disponibilidad = 1
    LIMIT ?
";

$stmt = $conexion->prepare($sql);
$stmt->bindParam(1, $tipo);
$stmt->bindParam(2, $cantidad, PDO::PARAM_INT);
$stmt->execute();

$bicicletas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($bicicletas) < $cantidad) {
    die("No hay suficientes bicicletas disponibles");
}

/* Calcular total */
$precio_dia = $bicicletas[0]['precio_renta'];
$total = $precio_dia * $dias * $cantidad;

$exito = false;

/* Confirmar renta */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {
        $conexion->beginTransaction();

        $sqlUpdate = "
            UPDATE bicicleta 
            SET disponibilidad = 0,
                fecha_renta = CURDATE(),
                fecha_disponible = NULL
            WHERE id_bicicleta = ?
        ";

        $stmtUpdate = $conexion->prepare($sqlUpdate);

        foreach ($bicicletas as $bici) {
            $stmtUpdate->execute([$bici['id_bicicleta']]);
        }

        $conexion->commit();
        $exito = true;

    } catch (Exception $e) {
        $conexion->rollBack();
        die("Error al confirmar la renta");
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar renta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../agregar_bici.css">
</head>
<body>

<nav>
    <ul class="navigation">
        <li><a href="../menu.php">← Volver al menú</a></li>
    </ul>
</nav>

<section class="products-section">

    <div class="product-card" style="max-width:420px;margin:auto">

        <h3><?= htmlspecialchars($tipo) ?></h3>

        <p>
            <strong>Cantidad:</strong> <?= $cantidad ?><br>
            <strong>Días:</strong> <?= $dias ?><br>
            <strong>Precio por día:</strong> $<?= number_format($precio_dia, 2) ?>
        </p>

        <hr>

        <p class="price">
            <strong>Total a pagar:</strong><br>
            $<?= number_format($total, 2) ?>
        </p>

        <?php if (!$exito): ?>
            <form method="POST">
                <button type="submit" class="button-glow" style="width:100%">
                    Confirmar renta 🚲
                </button>
            </form>
        <?php else: ?>
            <p style="color:green;font-weight:600;margin-top:15px">
                ✅ Renta confirmada correctamente
            </p>

            <p style="font-size:14px;margin-top:8px">
                Fecha de renta: <?= date("d/m/Y") ?><br>
                Fecha estimada de devolución: <?= date("d/m/Y", strtotime("+$dias days")) ?>
            </p>

            <a 
                href="../menu.php" 
                class="button-glow" 
                style="width:100%;margin-top:10px;display:flex;justify-content:center"
            >
                Volver al menú
            </a>
        <?php endif; ?>

    </div>

</section>

</body>
</html>
