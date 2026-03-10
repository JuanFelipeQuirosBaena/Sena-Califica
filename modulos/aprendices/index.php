<?php
session_start(); $rolesPermitidos = ['admin', 'instructor'];
if(!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol'], $rolesPermitidos)){
    header('Location: login.php'); exit();
} 
include '../../config/database.php'; $db=(new Database())->getConnection();
$busqueda = $_GET['busqueda'] ?? ''; $where=''; $params=[];
if($busqueda){ $where='WHERE a.documento LIKE ? OR a.nombre LIKE ? OR a.email LIKE ?'; $p='%'.$busqueda.'%'; $params=[$p,$p,$p]; }
$stmt = $db->prepare('SELECT a.*, f.numero_ficha FROM aprendices a LEFT JOIN fichas f ON a.ficha_id=f.id '.$where.' ORDER BY a.fecha_registro DESC'); $stmt->execute($params); $aprendices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Aprendices</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="../../css/style.css" rel="stylesheet"></head><body><?php include '../../includes/header.php'; ?>
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom"><h1 class="h2">Aprendices</h1><a href="crear.php" class="btn btn-warning"><i class="fas fa-user-plus"></i> Nuevo Aprendiz</a></div>
<div class="card mb-4"><div class="card-body"><form method="GET" class="row g-3"><div class="col-md-8"><input type="text" name="busqueda" class="form-control" placeholder="Buscar..." value="<?php echo htmlspecialchars($busqueda); ?>"></div><div class="col-md-4"><button class="btn btn-primary w-100">Buscar</button></div></form></div></div>
<div class="card"><div class="card-body table-responsive"><table class="table table-striped"><thead><tr><th>Documento</th><th>Nombre</th><th>Email</th><th>Ficha</th><th>Estado</th><th>Acciones</th></tr></thead><tbody><?php foreach($aprendices as $ap): ?><tr><td><?php echo htmlspecialchars($ap['documento']); ?></td><td><?php echo htmlspecialchars($ap['nombre'].' '.$ap['apellido']); ?></td><td><?php echo htmlspecialchars($ap['email']); ?></td><td><?php echo htmlspecialchars($ap['numero_ficha']); ?></td><td><span class="badge bg-<?php echo $ap['estado_academico']=='activo'?'success':'secondary'; ?>"><?php echo ucfirst($ap['estado_academico']); ?></span></td><td><a href="editar.php?id=<?php echo $ap['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a> <a href="eliminar.php?id=<?php echo $ap['id']; ?>" class="btn btn-sm btn-danger confirm-delete"><i class="fas fa-trash"></i></a></td></tr><?php endforeach; ?></tbody></table></div></div>
<?php include '../../includes/footer.php'; ?><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script><script src="../../js/script.js"></script></body></html>