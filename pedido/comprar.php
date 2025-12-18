<?php
require_once "../config/database.php";

if (!isset($_GET['id'])) {
    die("Bicicleta no válida");
}

$id = (int) $_GET['id'];

$sql = "
    SELECT id_bicicleta, tipo, precio_venta, imagen
    FROM bicicleta
    WHERE id_bicicleta = ? AND disponibilidad = 1
";

$stmt = $conexion->prepare($sql);
$stmt->execute([$id]);
$bici = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bici) {
    die("Bicicleta no disponible");
}

$exito = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $sqlUpdate = "
            UPDATE bicicleta
            SET disponibilidad = 0
            WHERE id_bicicleta = ?
        ";

        $stmtUpdate = $conexion->prepare($sqlUpdate);
        $stmtUpdate->execute([$id]);

        $exito = true;

    } catch (Exception $e) {
        die("Error al procesar la compra");
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprar Bicicleta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../agregar_bici.css">
</head>
<body>

<nav>
    <ul class="navigation">
        <li><a href="../menu.php">← Volver</a></li>
    </ul>
</nav>

<section class="products-section">

    <div class="product-card" style="max-width:420px;margin:auto">

        <img src="../<?= $bici['imagen'] ?>" alt="<?= htmlspecialchars($bici['tipo']) ?>">

        <h3><?= htmlspecialchars($bici['tipo']) ?></h3>

        <p class="price">
            Precio de venta: $<?= number_format($bici['precio_venta'], 2) ?>
        </p>

        <?php if (!$exito): ?>
            <form method="POST">
                <button type="submit" class="button-glow" style="width:100%">
                    Confirmar compra 🛒
                </button>
            </form>
        <?php else: ?>
            <p style="color:green;font-weight:600;margin-top:15px">
                ✅ Compra realizada correctamente
            </p>

            <a href="../menu.php"
               class="button-glow"
               style="width:100%;margin-top:10px;display:flex;justify-content:center">
                Volver al menú
            </a>
        <?php endif; ?>

    </div>

</section>

</body>
</html>
