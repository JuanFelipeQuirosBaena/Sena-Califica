<?php
require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Acceso denegado");
}

function clean($v) {
    return trim(htmlspecialchars($v));
}

// Capturar y limpiar datos del formulario
$nombre = clean($_POST["nombre"] ?? '');
$apellido = clean($_POST["apellido"] ?? '');
$tipo_documento = clean($_POST["tipo_documento"] ?? '');
$numero_documento = clean($_POST["numero_documento"] ?? '');
$correo = filter_var($_POST["correo"] ?? '', FILTER_VALIDATE_EMAIL);
$telefono = clean($_POST["telefono"] ?? '');
$password = $_POST["password"] ?? '';
$confirm = $_POST["confirm_password"] ?? '';

// Validaciones
if (empty($nombre) || empty($apellido)) {
    die("El nombre y apellido son obligatorios");
}

if (empty($tipo_documento) || empty($numero_documento)) {
    die("El tipo y número de documento son obligatorios");
}

if (!$correo) {
    die("Correo inválido");
}

if (strlen($password) < 8) {
    die("La contraseña debe tener al menos 8 caracteres");
}

if ($password !== $confirm) {
    die("Las contraseñas no coinciden");
}

// Hash de la contraseña
$hash = password_hash($password, PASSWORD_DEFAULT);

// Conectar a la base de datos usando PDO
$database = new Database();
$db = $database->getConnection();

// Verificar si el usuario ya existe (por correo o documento)
$sql = "SELECT * FROM usuarios WHERE email = ? OR documento = ? LIMIT 1";
$stmt = $db->prepare($sql);
$stmt->execute([$correo, $numero_documento]);

if ($stmt->rowCount() > 0) {
    die("El correo o número de documento ya está registrado.");
}

// Insertar nuevo usuario
$sql = "INSERT INTO usuarios (documento, nombre, apellido, email, password, rol, estado) 
        VALUES (?, ?, ?, ?, ?, 'aprendiz', 'activo')";

$stmt = $db->prepare($sql);

try {
    $stmt->execute([$numero_documento, $nombre, $apellido, $correo, $hash]);
    
    // Registro exitoso - redirigir al login
    header("Location: login.php?registro=exitoso");
    exit();
    
} catch (PDOException $e) {
    die("Error al registrar el usuario: " . $e->getMessage());
}
?>