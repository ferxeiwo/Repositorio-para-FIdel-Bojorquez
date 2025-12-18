<?php
require_once "config/database.php";

$sql = "
    SELECT 
        MIN(id_bicicleta) AS id_bicicleta,
        tipo,
        precio_renta,
        precio_venta,
        descuento,
        imagen,
        SUM(disponibilidad) AS disponibles
    FROM bicicleta
    WHERE disponibilidad = 1
    GROUP BY tipo, precio_renta, precio_venta, descuento, imagen
";

$stmt = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="stylemenu.css">
</head>

<body>

<nav>
    <ul class="navigation">

        <li>
            <a href="menu.php">Home</a>
        </li>

        <li>
            <a href="#productos">Bicicletas</a>
        </li>

        <li>
            <a href="#">Pedidos</a>
            <div class="subnavigation__wrapper">
                <ul>
                    <li><a href="pedido/rentar.php">Rentar</a></li>
                    <li><a href="pedido/devolver.php">Devolver</a></li>
                </ul>
            </div>
        </li>

        <li>
            <a href="#">Administrar</a>
            <div class="subnavigation__wrapper">
                <ul>
                    <li><a href="bicicletas/agregar_bici.php">Agregar bicicleta</a></li>
                </ul>
            </div>
        </li>

        <li>
            <a href="auth/login.php">Cerrar sesion</a>
        </li>

    </ul>
</nav>

<section id="productos" class="products-section">
    <h2>Nuestras Bicicletas</h2>

    <div class="products">
        <?php while ($bici = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="product-card">

                <?php if (!empty($bici['descuento']) && $bici['descuento'] > 0): ?>
                    <div class="discount">
                        -<?= $bici['descuento'] ?>%
                    </div>
                <?php endif; ?>

                <img 
                    src="<?= htmlspecialchars($bici['imagen']) ?>" 
                    alt="<?= htmlspecialchars($bici['tipo']) ?>"
                >

                <div class="product-info">
                    <p class="price">
                        Renta: $<?= number_format($bici['precio_renta'], 2) ?>
                    </p>

                    <p class="price">
                        Venta: $<?= number_format($bici['precio_venta'], 2) ?>
                    </p>

                    <p>
                        <strong>Disponibles:</strong> <?= $bici['disponibles'] ?>
                    </p>

                    <h4><?= htmlspecialchars($bici['tipo']) ?></h4>
                </div>

                <a 
                    class="add-btn"
                    href="pedido/elegir_accion.php?id=<?= $bici['id_bicicleta'] ?>"
                    title="Comprar o rentar"
                >
                    +
                </a>

            </div>
        <?php endwhile; ?>
    </div>
</section>

</body>
</html>
