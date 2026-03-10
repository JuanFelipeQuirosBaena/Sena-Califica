<?php
session_start(); if(!isset($_SESSION['usuario_id'])||$_SESSION['rol']!='admin'){ header("Location: index.php"); exit(); }
include '../../config/database.php'; $db=(new Database())->getConnection();
$id = $_GET['id'] ?? null; if(!$id){ header('Location: index.php'); exit(); }
$stmt = $db->prepare('SELECT * FROM aprendices WHERE id=?'); $stmt->execute([$id]); $ap = $stmt->fetch(PDO::FETCH_ASSOC); if(!$ap){ header('Location: index.php'); exit(); }
$fichas = $db->query('SELECT id,numero_ficha FROM fichas WHERE estado="activa"')->fetchAll(PDO::FETCH_ASSOC);
$error=''; $success='';
if($_POST){
    $documento = trim($_POST['documento']); $tipo = $_POST['tipo_documento'] ?? ''; $nombre = trim($_POST['nombre']); $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']); $telefono = trim($_POST['telefono']); $direccion = trim($_POST['direccion']); $ficha_id = $_POST['ficha_id'] ?? null;
    if(!$documento || !$tipo || !$nombre || !$apellido || !$email){ $error='Complete los campos obligatorios.'; }
    elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){ $error='Email inválido.'; }
    else{
        $stmt = $db->prepare('SELECT id FROM aprendices WHERE (email=? OR documento=?) AND id<>?'); $stmt->execute([$email,$documento,$id]);
        if($stmt->rowCount()>0) $error='Email o documento ya registrados.';
        else{
            if($ficha_id){
                $fs = $db->prepare('SELECT id FROM fichas WHERE id=?'); $fs->execute([$ficha_id]);
                if($fs->rowCount()==0){ $error='Ficha seleccionada no válida.'; }
            }
            if(!$error){
                $stmt = $db->prepare('UPDATE aprendices SET documento=?,tipo_documento=?,nombre=?,apellido=?,email=?,telefono=?,direccion=?,ficha_id=? WHERE id=?');
                if($stmt->execute([$documento,$tipo,$nombre,$apellido,$email,$telefono,$direccion,$ficha_id,$id])){ $success='Aprendiz actualizado.'; $stmt=$db->prepare('SELECT * FROM aprendices WHERE id=?'); $stmt->execute([$id]); $ap=$stmt->fetch(PDO::FETCH_ASSOC); }
                else $error='Error al actualizar.';
            }
        }
    }
}
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Editar Aprendiz</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="../../css/style.css" rel="stylesheet"></head><body><?php include '../../includes/header.php'; ?>
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom"><h1 class="h2">Editar Aprendiz</h1><a href="index.php" class="btn btn-secondary">Volver</a></div>
<?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?><?php if($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
<div class="card"><div class="card-body"><form method="POST" onsubmit="return validateForm(this)"><div class="row"><div class="col-md-6"><div class="mb-3"><label class="form-label">Documento</label><input type="text" name="documento" class="form-control" required value="<?php echo htmlspecialchars($ap['documento']); ?>"></div></div><div class="col-md-6"><div class="mb-3"><label class="form-label">Tipo documento</label><select name="tipo_documento" class="form-control" required><option value="">Seleccione</option><option value="CC" <?php if($ap['tipo_documento']=='CC') echo 'selected'; ?>>CC</option><option value="TI" <?php if($ap['tipo_documento']=='TI') echo 'selected'; ?>>TI</option><option value="CE" <?php if($ap['tipo_documento']=='CE') echo 'selected'; ?>>CE</option></select></div></div></div><div class="row"><div class="col-md-6"><div class="mb-3"><label class="form-label">Nombre</label><input type="text" name="nombre" class="form-control" required value="<?php echo htmlspecialchars($ap['nombre']); ?>"></div></div><div class="col-md-6"><div class="mb-3"><label class="form-label">Apellido</label><input type="text" name="apellido" class="form-control" required value="<?php echo htmlspecialchars($ap['apellido']); ?>"></div></div></div><div class="row"><div class="col-md-6"><div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($ap['email']); ?>"></div></div><div class="col-md-6"><div class="mb-3"><label class="form-label">Teléfono</label><input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($ap['telefono']); ?>"></div></div></div><div class="mb-3"><label class="form-label">Dirección</label><textarea name="direccion" class="form-control"><?php echo htmlspecialchars($ap['direccion']); ?></textarea></div><div class="mb-3"><label class="form-label">Ficha</label><select name="ficha_id" class="form-control"><option value="">Sin ficha</option><?php foreach($fichas as $fi): ?><option value="<?php echo $fi['id']; ?>" <?php if($ap['ficha_id']==$fi['id']) echo 'selected'; ?>><?php echo htmlspecialchars($fi['numero_ficha']); ?></option><?php endforeach; ?></select></div><button class="btn btn-primary">Actualizar Aprendiz</button></form></div></div>
<?php include '../../includes/footer.php'; ?><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script><script src="../../js/script.js"></script></body></html>