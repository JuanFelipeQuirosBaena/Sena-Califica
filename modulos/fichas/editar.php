<?php
session_start(); if(!isset($_SESSION['usuario_id'])||$_SESSION['rol']!='admin'){ header("Location: index.php"); exit(); }
include '../../config/database.php'; $db=(new Database())->getConnection();
$id = $_GET['id'] ?? null; if(!$id){ header('Location: index.php'); exit(); }
$stmt = $db->prepare('SELECT * FROM fichas WHERE id=?'); $stmt->execute([$id]); $f = $stmt->fetch(PDO::FETCH_ASSOC); if(!$f){ header('Location: index.php'); exit(); }
$error=''; $success='';
if($_POST){
    $numero = trim($_POST['numero_ficha']); $programa = trim($_POST['programa_formacion']); $jornada = $_POST['jornada'] ?? '';
    $fecha_inicio = $_POST['fecha_inicio'] ?? ''; $fecha_fin = $_POST['fecha_fin'] ?? '';
    if(!$numero || !$programa || !$jornada || !$fecha_inicio || !$fecha_fin){ $error='Complete todos los campos.'; }
    else{
        $stmt = $db->prepare('SELECT id FROM fichas WHERE numero_ficha=? AND id<>?'); $stmt->execute([$numero,$id]);
        if($stmt->rowCount()>0) $error='Número de ficha ya existe.';
        else{
            $stmt=$db->prepare('UPDATE fichas SET numero_ficha=?,programa_formacion=?,fecha_inicio=?,fecha_fin=?,jornada=? WHERE id=?');
            if($stmt->execute([$numero,$programa,$fecha_inicio,$fecha_fin,$jornada,$id])){ $success='Ficha actualizada.'; $stmt = $db->prepare('SELECT * FROM fichas WHERE id=?'); $stmt->execute([$id]); $f=$stmt->fetch(PDO::FETCH_ASSOC); }
            else $error='Error al actualizar ficha.';
        }
    }
}
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Editar Ficha</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="../../css/style.css" rel="stylesheet"></head><body><?php include '../../includes/header.php'; ?>
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom"><h1 class="h2">Editar Ficha</h1><a href="index.php" class="btn btn-secondary">Volver</a></div>
<?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?><?php if($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
<div class="card"><div class="card-body"><form method="POST" onsubmit="return validateForm(this)"><div class="row"><div class="col-md-6"><div class="mb-3"><label class="form-label">Número de ficha</label><input type="text" name="numero_ficha" class="form-control" required value="<?php echo htmlspecialchars($f['numero_ficha']); ?>"></div></div><div class="col-md=6"><div class="mb-3"><label class="form-label">Jornada</label><select name="jornada" class="form-control" required><option value="">Seleccione</option><option value="mañana" <?php if($f['jornada']=='mañana') echo 'selected'; ?>>Mañana</option><option value="tarde" <?php if($f['jornada']=='tarde') echo 'selected'; ?>>Tarde</option><option value="noche" <?php if($f['jornada']=='noche') echo 'selected'; ?>>Noche</option></select></div></div></div><div class="mb-3"><label class="form-label">Programa de formación</label><input type="text" name="programa_formacion" class="form-control" required value="<?php echo htmlspecialchars($f['programa_formacion']); ?>"></div><div class="row"><div class="col-md-6"><div class="mb-3"><label class="form-label">Fecha inicio</label><input type="date" name="fecha_inicio" class="form-control" required value="<?php echo $f['fecha_inicio']; ?>"></div></div><div class="col-md-6"><div class="mb-3"><label class="form-label">Fecha fin</label><input type="date" name="fecha_fin" class="form-control" required value="<?php echo $f['fecha_fin']; ?>"></div></div></div><button class="btn btn-primary">Actualizar Ficha</button></form></div></div>
<?php include '../../includes/footer.php'; ?><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script><script src="../../js/script.js"></script></body></html>