<?php
session_start(); if(!isset($_SESSION['usuario_id'])||$_SESSION['rol']!='admin'){ header("Location: index.php"); exit(); }
include '../../config/database.php'; $db=(new Database())->getConnection();
$id = $_GET['id'] ?? null; if(!$id){ header('Location: index.php'); exit(); }
$stmt = $db->prepare('SELECT * FROM actividades WHERE id=?'); $stmt->execute([$id]); $ac = $stmt->fetch(PDO::FETCH_ASSOC); if(!$ac){ header('Location: index.php'); exit(); }
$fichas = $db->query('SELECT id,numero_ficha FROM fichas WHERE estado="activa"')->fetchAll(PDO::FETCH_ASSOC);
$error=''; $success='';
if($_POST){
    $nombre = trim($_POST['nombre']); $descripcion = trim($_POST['descripcion']); $fecha_entrega = $_POST['fecha_entrega'] ?? ''; $ficha_id = $_POST['ficha_id'] ?? null; $estado = $_POST['estado'] ?? 'pendiente';
    if(!$nombre || !$fecha_entrega){ $error='Complete los campos obligatorios.'; }
    else{
        if($ficha_id){ $fs = $db->prepare('SELECT id FROM fichas WHERE id=?'); $fs->execute([$ficha_id]); if($fs->rowCount()==0){ $error='Ficha seleccionada no válida.'; } }
        if(!$error){
            $stmt = $db->prepare('UPDATE actividades SET nombre=?,descripcion=?,fecha_entrega=?,ficha_id=?,estado=? WHERE id=?');
            if($stmt->execute([$nombre,$descripcion,$fecha_entrega,$ficha_id,$estado,$id])){ $success='Actividad actualizada.'; $stmt=$db->prepare('SELECT * FROM actividades WHERE id=?'); $stmt->execute([$id]); $ac=$stmt->fetch(PDO::FETCH_ASSOC); }
            else $error='Error al actualizar.';
        }
    }
}
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Editar Actividad</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="../../css/style.css" rel="stylesheet"></head><body><?php include '../../includes/header.php'; ?>
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom"><h1 class="h2">Editar Actividad</h1><a href="index.php" class="btn btn-secondary">Volver</a></div>
<?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?><?php if($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
<div class="card"><div class="card-body"><form method="POST" onsubmit="return validateForm(this)"><div class="mb-3"><label class="form-label">Nombre</label><input type="text" name="nombre" class="form-control" required value="<?php echo htmlspecialchars($ac['nombre']); ?>"></div><div class="mb-3"><label class="form-label">Descripción</label><textarea name="descripcion" class="form-control"><?php echo htmlspecialchars($ac['descripcion']); ?></textarea></div><div class="row"><div class="col-md-6"><div class="mb-3"><label class="form-label">Fecha entrega</label><input type="datetime-local" name="fecha_entrega" class="form-control" required value="<?php echo date('Y-m-d\TH:i',strtotime($ac['fecha_entrega'])); ?>"></div></div><div class="col-md-6"><div class="mb-3"><label class="form-label">Ficha</label><select name="ficha_id" class="form-control"><option value="">Sin ficha</option><?php foreach($fichas as $fi): ?><option value="<?php echo $fi['id']; ?>" <?php if($ac['ficha_id']==$fi['id']) echo 'selected'; ?>><?php echo htmlspecialchars($fi['numero_ficha']); ?></option><?php endforeach; ?></select></div></div></div><div class="mb-3"><label class="form-label">Estado</label><select name="estado" class="form-control"><option value="pendiente" <?php if($ac['estado']=='pendiente') echo 'selected'; ?>>Pendiente</option><option value="en curso" <?php if($ac['estado']=='en curso') echo 'selected'; ?>>En curso</option><option value="finalizada" <?php if($ac['estado']=='finalizada') echo 'selected'; ?>>Finalizada</option></select></div><button class="btn btn-primary">Actualizar Actividad</button></form></div></div>
<?php include '../../includes/footer.php'; ?><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script><script src="../../js/script.js"></script></body></html>