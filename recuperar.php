<?php
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

$mensaje = "";
$mensaje_tipo = ""; // 'ok' o 'error'

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $query = "SELECT id FROM usuarios WHERE email = ? LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {

        // Crear token aleatorio
        $token = bin2hex(random_bytes(32));

        // Guardar token en BD
        $q = "UPDATE usuarios SET token_recuperacion = ?, token_expira = DATE_ADD(NOW(), INTERVAL 1 HOUR)
              WHERE email = ?";
        $st = $db->prepare($q);
        $st->execute([$token, $email]);

        // Enlace apuntando al proyecto
        $enlace = "http://localhost/sistema_administrativo/reset.php?token=" . $token;

        $mensaje = "Se generó un enlace de recuperación: <br><br>
                    <a href='$enlace'>Restablecer contraseña</a>";
        $mensaje_tipo = "ok";
    } else {
        $mensaje = "El correo no está registrado.";
        $mensaje_tipo = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .contenedor {
            background: white;
            padding: 35px;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #0066cc;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #004a99;
        }

        .volver {
            margin-top: 15px;
            display: block;
            color: #0066cc;
            text-decoration: none;
        }

        .mensaje {
            margin-top: 15px;
            color: green;
            font-weight: bold;
        }

        .error {
            margin-top: 15px;
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="contenedor">
    <h2>Recuperar contraseña</h2>

    <form action="recuperar.php" method="POST">
        <input type="email" name="email" placeholder="Ingresa tu correo" required>

        <button type="submit" name="buscar_correo">Enviar</button>

        <a href="login.php" class="volver">Volver al inicio de sesión</a>
    </form>

    <?php if (!empty($mensaje)): ?>
        <div class="<?php echo ($mensaje_tipo === 'ok') ? 'mensaje' : 'error'; ?>">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>