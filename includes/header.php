<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

// Usar URL absoluta para evitar 404 (ajusta si tu proyecto tiene otro nombre)
$baseUrl = 'http://localhost/sistema_administrativo/';

// Obtener la URI actual para marcar el menú activo
$currentUri = $_SERVER['REQUEST_URI'];
function isActive($segment) {
    global $currentUri;
    return (strpos($currentUri, $segment) !== false) ? ' active' : '';
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="<?php echo $baseUrl; ?>index.php">
        <i class="fas fa-graduation-cap"></i> Sistema Administrativo SENA
    </a>

    <div class="navbar-nav ms-auto">
      <span class="navbar-text text-white me-3">
        <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['nombre']); ?>
      </span>

      <!-- CERRAR SESIÓN -->
      <a href="<?php echo $baseUrl; ?>logout.php" class="btn btn-outline-light btn-sm">
        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
      </a>
    </div>
  </div>
</nav>

<style>
/* Asegura que los iconos siempre sean visibles y cambien con el estado activo */
.nav-link i { margin-right:8px; }
.nav-link.active, .nav-link.active i { color: #0d6efd; /* color para active (ajusta) */ }
</style>

<div class="container-fluid">
  <div class="row">

    <!-- MENÚ LATERAL -->
    <nav class="col-md-3 col-lg-2 sidebar">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">

          <li class="nav-item">
            <a class="nav-link<?php echo isActive('index.php'); ?>" href="<?php echo $baseUrl; ?>index.php">
              <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link<?php echo isActive('./modulos/usuarios'); ?>" href="<?php echo $baseUrl; ?>modulos/usuarios/">
              <i class="fas fa-users"></i> Usuarios
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link<?php echo isActive('modulos/fichas'); ?>" href="<?php echo $baseUrl; ?>modulos/fichas/">
              <i class="fas fa-file-alt"></i> Fichas
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link<?php echo isActive('modulos/aprendices'); ?>" href="<?php echo $baseUrl; ?>modulos/aprendices/">
              <i class="fas fa-user-graduate"></i> Aprendices
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link<?php echo isActive('modulos/actividades'); ?>" href="<?php echo $baseUrl; ?>modulos/actividades/">
              <i class="fas fa-tasks"></i> Actividades
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link<?php echo isActive('importar/fichas'); ?>" href="<?php echo $baseUrl; ?>importar/fichas.php">
              <i class="fas fa-upload"></i> Importar
            </a>
          </li>

        </ul>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
<script>
if (window.history && window.history.replaceState) {
  window.history.replaceState(null, null, window.location.href);
  window.onpopstate = function() {
    window.location.href = '/sistema_administrativo/login.php';
  };
}
</script>


