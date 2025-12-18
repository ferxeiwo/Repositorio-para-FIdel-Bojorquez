<?php
session_start();
require_once "../config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM usuario WHERE nombre_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["contrasena"])) {
        $_SESSION["id_usuario"] = $user["id_usuario"];
        $_SESSION["usuario"] = $user["nombre_usuario"];
        header("Location: ../menu.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <div class="form-wrapper">
        <main class="form-side">
            <form class="my-form" method="POST" action="">
                <div class="form-welcome-row">
                    <h1>Login! 👋</h1>
                    <h2>Accede a tu cuenta</h2>
                </div>

                <?php if ($error): ?>
                    <p style="color:red; text-align:center;">
                        <?= htmlspecialchars($error) ?>
                    </p>
                <?php endif; ?>

                <div class="divider">
                    <div class="divider-line"></div>
                    Login con Email
                    <div class="divider-line"></div>
                </div>

                <div class="text-field">
                    <label for="email">Usuario</label>
                    <input
                        type="text"
                        id="email"
                        name="email"
                        placeholder="usuario"
                        autocomplete="off"
                        required>
                </div>

                <div class="text-field">
                    <label for="password">Contraseña</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="Tu contraseña"
                        autocomplete="off"
                        required>
                        <span>¿Olvidaste tu contraseña?</span>
                        <a href="recuperar_contrasena.php">
                            Recuperar contraseña
                        </a>
                </div>

                <button class="my-form__button" type="submit">
                    Iniciar Sesión
                </button>

                <div class="my-form__actions">
                    <div class="my-form__row">
                        <span>¿No tienes cuenta?</span>
                        <a href="registro.php">
                            Regístrate
                        </a>
                    </div>
                </div>
            </form>
        </main>

        <aside class="info-side">
            <article class="blockquote-wrapper">
                <h2>¿Por qué iniciar sesión?</h2>
                <p>
                    Al iniciar sesión podrás rentar bicicletas, ver tus pedidos,
                    realizar pagos y acceder a tu panel personal.
                </p>
                <img src="../assets/dashboard.png" alt="Dashboard">
            </article>
        </aside>
    </div>
</body>
</html>
