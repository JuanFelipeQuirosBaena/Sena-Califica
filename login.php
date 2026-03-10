<?php
session_start();
if(isset($_SESSION['usuario_id'])){ header('Location: index.php'); exit(); }
include 'config/database.php';
$error='';
if($_POST){
    $db=(new Database())->getConnection();
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $db->prepare('SELECT * FROM usuarios WHERE email = ? AND estado = "activo"');
    $stmt->execute([$email]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
    if($u){
        $stored = $u['password'];
        if(function_exists('password_verify') && (strlen($stored)>=60 || substr($stored,0,4)==='$2y$' || substr($stored,0,4)==='$2b$')){
            if(password_verify($password, $stored)){
                $_SESSION['usuario_id'] = $u['id'];
                $_SESSION['nombre'] = $u['nombre'].' '.$u['apellido'];
                $_SESSION['rol'] = $u['rol'];
                header('Location: index.php'); exit();
            }
        } else {
            if($password === $stored){
                $_SESSION['usuario_id'] = $u['id'];
                $_SESSION['nombre'] = $u['nombre'].' '.$u['apellido'];
                $_SESSION['rol'] = $u['rol'];
                header('Location: index.php'); exit();
            }
        }
    }
    $error = 'Email o contraseña incorrectos';
}
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet"></head><body class="login-body">
<div class="login-container"><div class="login-card">
  <div class="text-center mb-4"><i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i><h2 class="fw-bold">Sistema Administrativo SENA</h2><p class="text-muted">Iniciar Sesión</p></div>
  <?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
  <form method="post" action="login.php">
    <div class="mb-3"><label class="form-label">Email</label><div class="input-group"><span class="input-group-text"><i class="fas fa-envelope"></i></span><input type="email" name="email" class="form-control" required></div></div>
    <div class="mb-3"><label class="form-label">Contraseña</label><div class="input-group"><span class="input-group-text"><i class="fas fa-lock"></i></span><input type="password" name="password" class="form-control" required></div></div>
    <button class="btn btn-primary w-100 mb-3"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</button>
  </form>

    <p style="text-align:center; margin-top:15px;">
        <a href="recuperar.php">¿Olvidaste tu contraseña?</a>
    </p>
    <p>¿No tienes cuenta? <a href="register.php" class="btn btn-link">Registrarse</a></p>
  <div class="text-center"><small class="text-muted">Usuario: admin@sena.com | Contraseña: 1234</small></div>
</div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/script.js"></script></body></html>