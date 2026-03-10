<?php
require_once __DIR__ . '/config.php';

// verificar conexión
if (!isset($conn) || $conn->connect_error) {
    die('Error de conexión a la base de datos.');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // limpiar datos
    $nombre     = trim($_POST['nombre'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $documento  = trim($_POST['documento'] ?? '');
    $rol        = $_POST['rol'] ?? '';
    $password   = $_POST['password'] ?? '';
    $confirm    = $_POST['confirm'] ?? '';

    // roles permitidos (SEGURIDAD)
    $roles_validos = ['admin', 'Instructor'];

    // VALIDACIONES
    if ($nombre === '' || $email === '' || $password === '' || $documento === '' || $rol === '') {
        $errors[] = 'Completa todos los campos.';
    }
    elseif (!in_array($rol, $roles_validos)) {
        $errors[] = 'Rol inválido.';
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email inválido.';
    }
    elseif ($password !== $confirm) {
        $errors[] = 'Las contraseñas no coinciden.';
    }
    else {

        // verificar email existente
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = 'El email ya está registrado.';
        } else {

            // verificar documento existente
            $stmt = $conn->prepare("SELECT id FROM usuarios WHERE documento = ? LIMIT 1");
            $stmt->bind_param("s", $documento);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $errors[] = 'El documento ya está registrado.';
            } else {

                // hash seguro
                $hash = password_hash($password, PASSWORD_DEFAULT);

                // insertar usuario
                $stmt = $conn->prepare(
                    "INSERT INTO usuarios (nombre, email, password, documento, rol)
                     VALUES (?, ?, ?, ?, ?)"
                );

                $stmt->bind_param("sssss", $nombre, $email, $hash, $documento, $rol);

                if ($stmt->execute()) {
                    header("Location: login.php?registered=1");
                    exit;
                } else {
                    $errors[] = "Error al crear la cuenta.";
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Registrarse</title>

<style>
body{
    font-family: Arial, Helvetica, sans-serif;
    background: linear-gradient(135deg,#39FA1E,#33D41C,#36C922);
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.container{
    background:#fff;
    padding:35px;
    width:380px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.25);
}

h1{
    text-align:center;
    margin-bottom:20px;
    color:#36C922;
}

label{
    font-weight:bold;
    font-size:14px;
}

input, select{
    width:100%;
    padding:10px;
    margin-top:5px;
    margin-bottom:15px;
    border-radius:6px;
    border:1px solid #ccc;
    box-sizing:border-box;
    transition:0.3s;
}

input:focus, select:focus{
    border-color:#36C922;
    outline:none;
    box-shadow:0 0 5px rgba(62, 230, 90, 0.3);
}

button{
    width:100%;
    padding:12px;
    background:#36C922;
    color:white;
    border:none;
    border-radius:6px;
    font-size:16px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{   
    background:#61e838;
}

.error-box{
    background:#ffe5e5;
    color:#b30000;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
}

.login-link{
    text-align:center;
    margin-top:15px;
}

.login-link a{
    color:#36C922;
    text-decoration:none;
    font-weight:bold;
}

.login-link a:hover{
    text-decoration:underline;
}
</style>
</head>

<body>

<div class="container">

<h1>Crear Cuenta</h1>

<?php if (!empty($errors)): ?>
<div class="error-box">
<ul>
<?php foreach ($errors as $e): ?>
<li><?= htmlspecialchars($e) ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

<form method="post">

<label>Nombre</label>
<input type="text" name="nombre"
value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">

<label>Documento</label>
<input type="text" name="documento"
value="<?= htmlspecialchars($_POST['documento'] ?? '') ?>">

<label>Email</label>
<input type="email" name="email"
value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

<label>Rol</label>
<select name="rol">
    <option value="">Seleccione un rol</option>
    <option value="admin" <?= (($_POST['rol'] ?? '')=='admin')?'selected':'' ?>>Administrador</option>
    <option value="Instructor" <?= (($_POST['rol'] ?? '')=='Instructor')?'selected':'' ?>>Instructor</option>
</select>

<label>Contraseña</label>
<input type="password" name="password">

<label>Confirmar contraseña</label>
<input type="password" name="confirm">

<button type="submit">Registrarse</button>

</form>

<div class="login-link">
<a href="login.php">← Volver al inicio de sesión</a>
</div>

</div>

</body>
</html>