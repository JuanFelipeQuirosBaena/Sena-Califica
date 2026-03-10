<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$db = (new Database())->getConnection();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../../includes/header.php'; ?>

<div class="container mt-4">
    <h2><i class="fas fa-users"></i> Gestión de Usuarios</h2>
    <p>Aquí puedes gestionar los usuarios del sistema.</p>
    
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> 
        Módulo en construcción - Próximamente...
    </div>
    
    <a href="crear.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nuevo Usuario
    </a>
</div>

<?php include '../../includes/footer.php'; ?>
</body>
</html>