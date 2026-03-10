<?php
session_start();

// Evitar cache del navegador
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Comprobar sesión
if (!isset($_SESSION['user_id'])) {
    session_unset();
    session_destroy();
    header("Location: /sistema_administrativo/login.php");
    exit;
}
?>