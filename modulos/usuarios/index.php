<?php
session_start(); $rolesPermitidos = ['admin', 'instructor'];
if(!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol'], $rolesPermitidos)){
    header('Location: login.php'); exit();
}
include '../../config/database.php'; $db=(new Database())->getConnection();
$busqueda = $_GET['busqueda'] ?? ''; $where=''; $params=[];
if($busqueda){ $where = 'WHERE documento LIKE ? OR nombre LIKE ? OR email LIKE ?'; $p='%'.$busqueda.'%'; $params=[$p,$p,$p]; }
$stmt = $db->prepare('SELECT * FROM usuarios '.$where.' ORDER BY fecha_creacion DESC'); $stmt->execute($params); $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Usuarios</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="../../css/style.css" rel="stylesheet"></head><body><?php include '../../includes/header.php'; ?>
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom"><h1 class="h2">Gestión de Usuarios</h1><a href="crear.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Nuevo Usuario</a></div>
<div class="card mb-4"><div class="card-body"><form method="GET" class="row g-3"><div class="col-md-8"><input type="text" name="busqueda" class="form-control" placeholder="Buscar..." value="<?php echo htmlspecialchars($busqueda); ?>"></div><div class="col-md-4"><button class="btn btn-primary w-100">Buscar</button></div></form></div></div>
<div class="card"><div class="card-body table-responsive"><table class="table table-striped"><thead><tr><th>Documento</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Estado</th><th>Fecha</th><th>Acciones</th></tr></thead><tbody><?php foreach($usuarios as $u): ?><tr><td><?php echo htmlspecialchars($u['documento']); ?></td><td><?php echo htmlspecialchars($u['nombre'].' '.$u['apellido']); ?></td><td><?php echo htmlspecialchars($u['email']); ?></td><td><span class="badge bg-<?php echo $u['rol']=='admin' ? 'danger' : ($u['rol']=='instructor' ? 'warning' : 'info'); ?>"><?php echo ucfirst($u['rol']); ?></span></td><td><span class="badge bg-<?php echo $u['estado']=='activo' ? 'success' : 'secondary'; ?>"><?php echo ucfirst($u['estado']); ?></span></td><td><?php echo date('d/m/Y',strtotime($u['fecha_creacion'])); ?></td><td><a href="editar.php?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a> <a href="eliminar.php?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-danger confirm-delete"><i class="fas fa-trash"></i></a></td></tr><?php endforeach; ?></tbody></table></div></div>
<?php include '../../includes/footer.php'; ?><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script><script src="../../js/script.js"></script></body></html>