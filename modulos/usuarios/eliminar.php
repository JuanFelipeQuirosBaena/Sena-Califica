<?php
session_start(); if(!isset($_SESSION['usuario_id'])||$_SESSION['rol']!='admin'){header("Location: index.php");
exit;
 }
include '../../config/database.php'; $db=(new Database())->getConnection();
$id = $_GET['id'] ?? null; if(!$id){ header('Location: index.php'); exit(); }
$stmt = $db->prepare('DELETE FROM usuarios WHERE id=?'); $stmt->execute([$id]);
header('Location: index.php'); exit();
?>