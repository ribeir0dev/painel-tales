<?php
session_start();
require_once '../config/conexao.php';

$erro = '';
$sucesso = '';

// Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user = $_POST['username'] ?? '';
    $pass = md5($_POST['password'] ?? '');

    $sql = "SELECT * FROM dbo.account_login WHERE name = ?";
    $stmt = $connAccountServer->prepare($sql);
    $stmt->execute([$user]);
    $account = $stmt->fetch();

    if ($account && $account['password'] === $pass) {
        $_SESSION['user'] = $user;
        header("Location: ../player/dashboard.php");
        exit;
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}

// Registro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $user = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $pass = md5($_POST['password'] ?? '');

    $check = $connAccountServer->prepare("SELECT * FROM dbo.account_login WHERE name = ?");
    $check->execute([$user]);

    if ($check->rowCount() > 0) {
        $erro = "Usuário já existe.";
    } else {
        $stmt = $connAccountServer->prepare("INSERT INTO dbo.account_login (name, password, email) VALUES (?, ?, ?)");
        if ($stmt->execute([$user, $pass, $email])) {
            $sucesso = "Conta criada com sucesso!";
        } else {
            $erro = "Erro ao registrar.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login e Registro</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark-custom">

<div class="auth-container">
    <div class="auth-box shadow">
        <img src="../assets/img/logo.png" alt="Logo" class="mb-3" style="width:120px;">
        <?php if ($erro): ?>
            <div class="alert alert-danger p-1"><?= $erro ?></div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="alert alert-success p-1"><?= $sucesso ?></div>
        <?php endif; ?>

        <!-- LOGIN FORM -->
        <form method="POST" class="mb-4">
            <h4 class="text-white">Login</h4>
            <input type="text" name="username" class="form-control neon-input mb-2" placeholder="Usuário" required>
            <input type="password" name="password" class="form-control neon-input mb-2" placeholder="Senha" required>
            <button type="submit" name="login" class="btn neon-btn w-100">Entrar</button>
        </form>

        <!-- REGISTRO FORM -->
        <form method="POST">
            <h4 class="text-white">Registrar</h4>
            <input type="text" name="username" class="form-control neon-input mb-2" placeholder="Novo Usuário" required>
            <input type="email" name="email" class="form-control neon-input mb-2" placeholder="Email" required>
            <input type="password" name="password" class="form-control neon-input mb-2" placeholder="Senha" required>
            <button type="submit" name="register" class="btn btn-outline-light w-100">Registrar</button>
        </form>
    </div>
</div>

</body>
</html>
