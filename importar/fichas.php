<?php
session_start();

$rolesPermitidos = ['admin', 'instructor'];

if(!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol'], $rolesPermitidos)){
    header('Location: ../login.php');
    exit();
}

include '../config/database.php';
$db = (new Database())->getConnection();

$mensaje = '';

if(isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0){

    $archivo = $_FILES['archivo']['tmp_name'];
    $nombreArchivo = $_FILES['archivo']['name'];

    // ✅ validar extensión correctamente
    $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

    if($extension === 'csv'){

        if(($handle = fopen($archivo, "r")) !== FALSE){

            // ✅ saltar encabezados del CSV
            fgetcsv($handle, 1000, ";");

            $contador = 0;

            while(($data = fgetcsv($handle, 1000, ";")) !== FALSE){

                // ✅ evitar errores de columnas faltantes
                if(count($data) < 5){
                    continue;
                }

                // limpiar datos
                $numero_ficha = trim($data[0]);
                $programa     = trim($data[1]);
                $fecha_inicio = trim($data[2]);
                $fecha_fin    = trim($data[3]);
                $jornada      = trim($data[4]);

                // ✅ evitar registros vacíos
                if(empty($numero_ficha) || empty($programa)){
                    continue;
                }

                // insertar ficha
                $stmt = $db->prepare(
                    'INSERT INTO fichas
                    (numero_ficha, programa_formacion, fecha_inicio, fecha_fin, jornada)
                    VALUES (?,?,?,?,?)'
                );

                if($stmt->execute([
                    $numero_ficha,
                    $programa,
                    $fecha_inicio,
                    $fecha_fin,
                    $jornada
                ])){
                    $contador++;
                }
            }

            fclose($handle);

            $mensaje = "✅ Se importaron $contador fichas correctamente.";

        }else{
            $mensaje = "❌ Error al abrir el archivo.";
        }

    }else{
        $mensaje = "❌ Solo se permiten archivos CSV.";
    }
}
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Importar Fichas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/style.css" rel="stylesheet">
</head>

<body>

<?php include '../includes/header.php'; ?>

<div class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
    <h1 class="h2">📂 Importar Fichas desde CSV</h1>
</div>

<?php if($mensaje): ?>
<div class="alert alert-info">
    <?php echo $mensaje; ?>
</div>
<?php endif; ?>

<div class="card shadow-sm">
<div class="card-body">

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">
<label class="form-label">Seleccione archivo CSV</label>

<input type="file"
       name="archivo"
       class="form-control"
       accept=".csv"
       required>

<div class="form-text">
Formato esperado:<br>
Número ficha ; Programa ; Fecha inicio (YYYY-MM-DD) ; Fecha fin ; Jornada
</div>
</div>

<button class="btn btn-primary">
    <i class="fas fa-upload"></i> Importar fichas
</button>

</form>

</div>
</div>

</div>

<?php include '../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/script.js"></script>

</body>
</html>