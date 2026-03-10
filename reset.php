<?php
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

$token = $_GET['token'] ?? null;
$mensaje = "";
$error = "";

if (!$token) {
    $error = "Token inválido.";
} else {
    $query = "SELECT id FROM usuarios WHERE token_recuperacion = ? 
              AND token_expira > NOW() LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->execute([$token]);

    if ($stmt->rowCount() === 0) {
        $error = "El enlace expiró o es inválido.";
    } else {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = $usuario['id'];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $password = $_POST['password'] ?? '';

            if (strlen($password) < 6) {
                $error = "La contraseña debe tener al menos 6 caracteres.";
            } else {
                $nueva = password_hash($password, PASSWORD_DEFAULT);

                $q = "UPDATE usuarios SET password = ?, token_recuperacion = NULL, token_expira = NULL
                      WHERE id = ?";
                $st = $db->prepare($q);
                $st->execute([$nueva, $id]);

                $mensaje = "Contraseña actualizada con éxito. Ya puedes iniciar sesión.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>

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
            width: 380px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.15);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
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

        .volver {
            display: block;
            margin-top: 20px;
            padding: 10px;
            background: #e6e6e6;
            border-radius: 6px;
            text-decoration: none;
            color: #333;
            font-size: 15px;
            font-weight: bold;
        }

        .volver:hover {
            background: #ccc;
        }
    </style>
</head>

<body>

<div class="contenedor">
    <h2>Restablecer contraseña</h2>

    <form method="POST" action="">
        <label>Nueva contraseña</label><br>
        <input type="password" name="password" required>

        <button type="submit" name="cambiar_pass">Cambiar contraseña</button>
    </form>

    <?php if(!empty($mensaje)) { ?>
        <p class="mensaje"><?php echo $mensaje; ?></p>
    <?php } ?>

    <?php if(!empty($error)) { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>

    <!-- BOTÓN PARA VOLVER AL LOGIN -->
    <a class="volver" href="login.php">Volver al inicio de sesión</a>

</div>

</body>
</html>