<?php
require_once "../config/database.php";

/* Obtener modelos actualmente rentados */
$sql = "
    SELECT tipo, COUNT(*) AS rentadas 
    FROM bicicleta 
    WHERE disponibilidad = 0
    GROUP BY tipo
";
$stmt = $conexion->query($sql);
$modelos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Devolver Bicicletas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

        <h3>Devolver bicicletas 🚲</h3>

        <?php if (count($modelos) === 0): ?>
            <p>No hay bicicletas rentadas.</p>
        <?php else: ?>

        <form method="POST" action="procesar_devolucion.php">

            <select name="tipo" required>
                <option value="">Selecciona modelo</option>
                <?php foreach ($modelos as $m): ?>
                    <option value="<?= htmlspecialchars($m['tipo']) ?>">
                        <?= $m['tipo'] ?> (<?= $m['rentadas'] ?> rentadas)
                    </option>
                <?php endforeach; ?>
            </select>

            <input
                type="number"
                name="cantidad"
                min="1"
                placeholder="¿Cuántas se devuelven?"
                required
            >

            <button type="submit" class="button-glow" style="width:100%">
                Confirmar devolución
            </button>

        </form>

        <?php endif; ?>

    </div>

</section>

</body>
</html>
