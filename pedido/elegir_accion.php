<?php
$id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>¿Qué deseas hacer?</title>
    <link rel="stylesheet" href="../agregar_bici.css">
</head>
<body>

<section class="products-section" style="text-align:center">

    <h2>¿Qué deseas hacer con esta bicicleta?</h2>

    <div style="margin-top:30px; display:flex; gap:20px; justify-content:center">

        <a href="comprar.php?id=<?= $id ?>" class="button-glow">
            Comprar
        </a>

        <a href="rentar.php?id=<?= $id ?>" class="button-glow">
            Rentar
        </a>

    </div>

</section>

</body>
</html>
