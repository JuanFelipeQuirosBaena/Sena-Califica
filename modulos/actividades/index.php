<?php
session_start(); $rolesPermitidos = ['admin', 'instructor'];
if(!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol'], $rolesPermitidos)){
    header('Location: login.php'); exit();
}
include '../../config/database.php'; $db=(new Database())->getConnection();
$busqueda = $_GET['busqueda'] ?? ''; $where=''; $params=[];
if($busqueda){ $where='WHERE a.nombre LIKE ? OR a.descripcion LIKE ?'; $p='%'.$busqueda.'%'; $params=[$p,$p]; }
$stmt = $db->prepare('SELECT a.*, f.numero_ficha, u.nombre AS creador_nombre FROM actividades a LEFT JOIN fichas f ON a.ficha_id=f.id LEFT JOIN usuarios u ON a.creador_id=u.id '.$where.' ORDER BY a.fecha_creacion DESC'); $stmt->execute($params); $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Actividades</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="../../css/style.css" rel="stylesheet"></head><body><?php include '../../includes/header.php'; ?>
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom"><h1 class="h2">Actividades</h1><a href="crear.php" class="btn btn-info"><i class="fas fa-tasks"></i> Nueva Actividad</a></div>
<div class="card mb-4"><div class="card-body"><form method="GET" class="row g-3"><div class="col-md-8"><input type="text" name="busqueda" class="form-control" placeholder="Buscar..." value="<?php echo htmlspecialchars($busqueda); ?>"></div><div class="col-md-4"><button class="btn btn-primary w-100">Buscar</button></div></form></div></div>
<div class="card"><div class="card-body table-responsive"><table class="table table-striped"><thead><tr><th>Nombre</th><th>Ficha</th><th>Creador</th><th>Entrega</th><th>Estado</th><th>Acciones</th></tr></thead><tbody><?php foreach($actividades as $ac): ?><tr><td><?php echo htmlspecialchars($ac['nombre']); ?></td><td><?php echo htmlspecialchars($ac['numero_ficha']); ?></td><td><?php echo htmlspecialchars($ac['creador_nombre']); ?></td><td><?php echo date('d/m/Y H:i',strtotime($ac['fecha_entrega'])); ?></td><td><span class="badge bg-<?php echo $ac['estado']=='pendiente'?'secondary':($ac['estado']=='en curso'?'warning':'success'); ?>"><?php echo ucfirst($ac['estado']); ?></span></td><td><a href="editar.php?id=<?php echo $ac['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a> <a href="eliminar.php?id=<?php echo $ac['id']; ?>" class="btn btn-sm btn-danger confirm-delete"><i class="fas fa-trash"></i></a></td></tr><?php endforeach; ?></tbody></table></div></div>
<?php include '../../includes/footer.php'; ?><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script><script src="../../js/script.js"></script></body></html>