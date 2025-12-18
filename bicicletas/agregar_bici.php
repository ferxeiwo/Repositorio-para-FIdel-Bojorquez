<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config/database.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $tipo = trim($_POST["tipo"]);
    $precio_renta = $_POST["precio_renta"];
    $precio_venta = $_POST["precio_venta"] ?? null;
    $descuento = $_POST["descuento"] ?? 0;
    $cantidad = (int) $_POST["cantidad"];

    if ($cantidad <= 0) {
        $msg = "⚠️ La cantidad debe ser mayor a 0";
    } elseif (!empty($_FILES["imagen"]["name"])) {

        $imagen = $_FILES["imagen"];
        $extension = strtolower(pathinfo($imagen["name"], PATHINFO_EXTENSION));
        $permitidas = ["jpg", "jpeg", "png", "webp"];

        if (!in_array($extension, $permitidas)) {
            $msg = "⚠️ Solo se permiten imágenes JPG, PNG o WEBP";
        } else {

            /* asegurar carpeta uploads */
            $carpeta = __DIR__ . "/../uploads/";
            if (!is_dir($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            $nombre = time() . "_" . uniqid() . "." . $extension;
            $ruta = "uploads/" . $nombre;

            if (!move_uploaded_file($imagen["tmp_name"], $carpeta . $nombre)) {
                $msg = "⚠️ Error al subir la imagen";
            } else {

                $sql = "INSERT INTO bicicleta (
                            tipo,
                            precio_venta,
                            precio_renta,
                            descuento,
                            disponibilidad,
                            estado_mantenimiento,
                            imagen
                        ) VALUES (?, ?, ?, ?, 1, 'disponible', ?)";

                $stmt = $conexion->prepare($sql);

                try {
                    $conexion->beginTransaction();

                    for ($i = 0; $i < $cantidad; $i++) {
                        $stmt->execute([
                            $tipo,
                            $precio_venta,
                            $precio_renta,
                            $descuento,
                            $ruta
                        ]);
                    }

                    $conexion->commit();
                    $msg = "✅ $cantidad bicicletas agregadas correctamente 🚲";

                } catch (Exception $e) {
                    $conexion->rollBack();
                    $msg = "❌ Error al guardar las bicicletas";
                }
            }
        }

    } else {
        $msg = "⚠️ Debes subir una imagen";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Bicicleta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../agregar_bici.css">
</head>
<body>

<nav>
    <ul class="navigation">
        <li><a href="../menu.php">Menú</a></li>
    </ul>
</nav>

<section class="products-section">

    <h2>Agregar Bicicleta</h2>

    <form method="POST" enctype="multipart/form-data" class="product-card" style="max-width:400px">

        <input type="text" name="tipo" placeholder="Tipo de bicicleta" required>

        <input type="number" name="precio_renta" placeholder="Precio renta" step="0.01" required>

        <input type="number" name="precio_venta" placeholder="Precio venta" step="0.01">

        <input type="number" name="descuento" placeholder="Descuento (%)">

        <input
            type="number"
            name="cantidad"
            min="1"
            placeholder="Cantidad de bicicletas"
            required
        >

        <input type="file" name="imagen" accept="image/*" required>

        <button type="submit" class="button-glow">
            Guardar 🚲
        </button>

    </form>

    <?php if ($msg): ?>
        <p style="margin-top:15px;font-weight:bold"><?= $msg ?></p>
    <?php endif; ?>

</section>

</body>
</html>
