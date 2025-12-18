<?php
require_once "../config/database.php";

if (!isset($_GET['id'])) {
    die("Bicicleta no válida");
}

$id = (int) $_GET['id'];

$sql = "
    SELECT tipo, precio_renta, imagen
    FROM bicicleta
    WHERE id_bicicleta = ?
";

$stmt = $conexion->prepare($sql);
$stmt->execute([$id]);
$base = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$base) {
    die("Bicicleta no encontrada");
}

$tipo = $base['tipo'];

$sql = "
    SELECT COUNT(*) AS disponibles
    FROM bicicleta
    WHERE tipo = ? AND disponibilidad = 1
";

$stmt = $conexion->prepare($sql);
$stmt->execute([$tipo]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($data['disponibles'] == 0) {
    die("No hay bicicletas disponibles");
}

$total = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dias = (int) $_POST['dias'];
    $cantidad = (int) $_POST['cantidad'];

    if ($dias > 0 && $cantidad > 0 && $cantidad <= $data['disponibles']) {
        $total = $dias * $cantidad * $base['precio_renta'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Rentar Bicicleta</title>
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

        <img src="../<?= $base['imagen'] ?>" alt="<?= htmlspecialchars($tipo) ?>">

        <h3><?= htmlspecialchars($tipo) ?></h3>

        <p class="price">
            Precio por día: $<?= number_format($base['precio_renta'], 2) ?>
        </p>

        <p>
            Disponibles: <strong><?= $data['disponibles'] ?></strong>
        </p>

<form method="POST" class="rent-form">

    <div class="rent-inputs">
        <input
            type="number"
            name="cantidad"
            min="1"
            max="<?= $data['disponibles'] ?>"
            placeholder="¿Cuántas bicicletas?"
            required
        >

        <input
            type="number"
            name="dias"
            min="1"
            placeholder="¿Cuántos días?"
            required
        >
    </div>

    <button type="submit" class="button-glow" style="width:100%">
        Calcular total
    </button>
</form>


        <?php if ($total !== null): ?>
            <hr style="margin:15px 0">

            <p>
                <strong>Total a pagar:</strong><br>
                $<?= number_format($total, 2) ?>
            </p>

            <a
                href="confirmar_renta.php?tipo=<?= urlencode($tipo) ?>&cantidad=<?= $cantidad ?>&dias=<?= $dias ?>"
                class="button-glow"
                style="width:100%;margin-top:10px;display:flex;justify-content:center"
            >
                Confirmar renta 🚲
            </a>
        <?php endif; ?>

    </div>

</section>

</body>
</html>
