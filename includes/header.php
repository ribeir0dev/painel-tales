<?php
if (!isset($_SESSION)) session_start();
require_once 'config/conexao.php';
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fontawesome.com/v4/assets/font-awesome/css/font-awesome.css" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/a2c9d6e7e1.js" crossorigin="anonymous"></script>
</head>
<body class="dashboard-dark">
<div class="d-flex" style="min-height: 100vh;">