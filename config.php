<?php
// Configuración de la conexión MySQL — ajusta valores según tu entorno
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'sistema_administrativo'; // cambia al nombre real de tu BD

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die('Conexión fallida a la base de datos: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');