<?php
session_start();
// if(!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin'){ header('Location: login.php'); exit(); }
$rolesPermitidos = ['admin', 'instructor'];
if(!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol'], $rolesPermitidos)){
    header('Location: login.php'); exit();
}
include 'config/database.php';
$db=(new Database())->getConnection();
$stats = [];
$stats['usuarios'] = $db->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();
$stats['fichas'] = $db->query("SELECT COUNT(*) FROM fichas WHERE estado='activa'")->fetchColumn();
$stats['aprendices'] = $db->query("SELECT COUNT(*) FROM aprendices WHERE estado_academico='activo'")->fetchColumn();
$stats['actividades'] = $db->query('SELECT COUNT(*) FROM actividades')->fetchColumn();
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet"></head><body>
<?php include 'includes/header.php'; ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"><h1 class="h2">Dashboard</h1></div>
<div class="row">
  <div class="col-md-3 mb-4"><div class="card text-white bg-primary"><div class="card-body"><div class="d-flex justify-content-between"><div><h4><?php echo $stats['usuarios']; ?></h4><p>Usuarios</p></div><div class="align-self-center"><i class="fas fa-users fa-2x"></i></div></div></div></div></div>
  <div class="col-md-3 mb-4"><div class="card text-white bg-success"><div class="card-body"><div class="d-flex justify-content-between"><div><h4><?php echo $stats['fichas']; ?></h4><p>Fichas Activas</p></div><div class="align-self-center"><i class="fas fa-file-alt fa-2x"></i></div></div></div></div></div>
  <div class="col-md-3 mb-4"><div class="card text-white bg-warning"><div class="card-body"><div class="d-flex justify-content-between"><div><h4><?php echo $stats['aprendices']; ?></h4><p>Aprendices</p></div><div class="align-self-center"><i class="fas fa-user-graduate fa-2x"></i></div></div></div></div></div>
  <div class="col-md-3 mb-4"><div class="card text-white bg-info"><div class="card-body"><div class="d-flex justify-content-between"><div><h4><?php echo $stats['actividades']; ?></h4><p>Actividades</p></div><div class="align-self-center"><i class="fas fa-tasks fa-2x"></i></div></div></div></div></div>
</div>
<div class="row mt-4"><div class="col-12"><h4>Acciones Rápidas</h4><div class="d-grid gap-2 d-md-flex">
<a href="modulos/usuarios/crear.php" class="btn btn-primary me-2"><i class="fas fa-user-plus"></i> Nuevo Usuario</a>
<a href="modulos/fichas/crear.php" class="btn btn-success me-2"><i class="fas fa-plus-circle"></i> Nueva Ficha</a>
<a href="modulos/aprendices/crear.php" class="btn btn-warning me-2"><i class="fas fa-user-plus"></i> Nuevo Aprendiz</a>
<a href="modulos/actividades/crear.php" class="btn btn-info"><i class="fas fa-tasks"></i> Nueva Actividad</a>
</div></div></div>
<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script><script src="js/script.js"></script></body></html>