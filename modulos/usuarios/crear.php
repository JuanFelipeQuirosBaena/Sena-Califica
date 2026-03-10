<?php
session_start(); if(!isset($_SESSION['usuario_id'])||$_SESSION['rol']!='admin'){ header("Location: index.php");
exit;
 }
include '../../config/database.php'; $db=(new Database())->getConnection();
$error=''; $success='';
if($_POST){
    $documento = trim($_POST['documento']); $nombre = trim($_POST['nombre']); $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']); $password = $_POST['password']; $rol = $_POST['rol'] ?? 'instructor';
    if(!$documento || !$nombre || !$apellido || !$email || !$password){ $error='Complete todos los campos.'; }
    elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){ $error='Email inválido.'; }
    else{
        $stmt = $db->prepare('SELECT id FROM usuarios WHERE email=? OR documento=?'); $stmt->execute([$email,$documento]);
        if($stmt->rowCount()>0) $error='Email o documento ya registrados.';
        else{
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare('INSERT INTO usuarios (documento,nombre,apellido,email,password,rol) VALUES (?,?,?,?,?,?)');
            if($stmt->execute([$documento,$nombre,$apellido,$email,$pass_hash,$rol])) $success='Usuario creado correctamente.'; else $error='Error al crear usuario.';
        }
    }
}
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Crear Usuario</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="../../css/style.css" rel="stylesheet"></head><body><?php include '../../includes/header.php'; ?>
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom"><h1 class="h2">Crear Usuario</h1><a href="index.php" class="btn btn-secondary">Volver</a></div>
<?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?><?php if($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
<div class="card"><div class="card-body"><form method="POST" onsubmit="return validateForm(this)"><div class="row"><div class="col-md-6"><div class="mb-3"><label class="form-label">Documento</label><input type="text" name="documento" class="form-control" required></div></div><div class="col-md-6"><div class="mb-3"><label class="form-label">Rol</label><select name="rol" class="form-control" required><option value="instructor">Instructor</option><option value="aprendiz">Aprendiz</option><option value="admin">Administrador</option></select></div></div></div><div class="row"><div class="col-md-6"><div class="mb-3"><label class="form-label">Nombre</label><input type="text" name="nombre" class="form-control" required></div></div><div class="col-md-6"><div class="mb-3"><label class="form-label">Apellido</label><input type="text" name="apellido" class="form-control" required></div></div></div><div class="row"><div class="col-md-6"><div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div></div><div class="col-md-6"><div class="mb-3"><label class="form-label">Contraseña</label><input type="password" name="password" class="form-control" required></div></div></div><button class="btn btn-primary">Crear Usuario</button></form></div></div>
<?php include '../../includes/footer.php'; ?><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script><script src="../../js/script.js"></script></body></html>