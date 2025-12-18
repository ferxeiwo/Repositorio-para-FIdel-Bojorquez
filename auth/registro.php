<?php
require_once "../config/database.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm = $_POST["confirm_password"];

    if ($password !== $confirm) {
        $error = "Las contraseñas no coinciden";
    } else {
        // Verificar si el usuario ya existe
        $sql = "SELECT id_usuario FROM usuario WHERE nombre_usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $error = "El usuario ya está registrado";
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuario (nombre_usuario, contrasena) VALUES (?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$email, $passwordHash]);

            $success = "Registro exitoso. Ahora puedes iniciar sesión.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Signup</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Mulish&display=swap"
        rel="stylesheet"
        type="text/css"
    >
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <div class="form-wrapper">
        <div class="form-side">
            <a href="../index.php" title="Logo">
                <img class="logo" src="../assets/logo.png" alt="Logo">
            </a>

            <form class="my-form" method="POST" action="">
                <div class="login-welcome-row">
                    <h1>Create your account </h1>
                </div>

                <?php if ($error): ?>
                    <p style="color:red; text-align:center;">
                        <?= htmlspecialchars($error) ?>
                    </p>
                <?php endif; ?>

                <?php if ($success): ?>
                    <p style="color:green; text-align:center;">
                        <?= htmlspecialchars($success) ?>
                    </p>
                <?php endif; ?>

                <div class="divider">
                    <span class="divider-line"></span>
                    Or
                    <span class="divider-line"></span>
                </div>

                <div class="text-field">
                    <label for="email">Email:
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Your Email"
                            autocomplete="off"
                            required
                        >
                    </label>
                </div>

                <div class="text-field">
                    <label for="password">Password:
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Your Password"
                            pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$"
                            required
                        >
                    </label>
                </div>

                <div class="text-field">
                    <label for="confirm-password">Repeat Password:
                        <input
                            id="confirm-password"
                            type="password"
                            name="confirm_password"
                            placeholder="Repeat Password"
                            pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$"
                            required
                        >
                    </label>
                </div>

                <button class="my-form__button" type="submit">
                    Sign up
                </button>

                <div class="my-form__actions">
                    <div class="my-form__signup">
                        <a href="login.php">
                            Login
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="info-side">
            <img src="../assets/mock.png" alt="Mock" class="mockup" />
            <div class="welcome-message">
                <h2>Bienvenido 👋</h2>
                <p>
                    Crea tu cuenta para rentar bicicletas, gestionar pedidos
                    y acceder a tu panel personal.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
