<?php
session_start();
require_once 'config/conexao.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formType = $_POST['form_type'] ?? '';

    if ($formType === 'login') {
        $user = $_POST['username'] ?? '';
        $rawPass = $_POST['password'] ?? '';

        if (strlen($rawPass) < 8 || strlen($rawPass) > 12) {
            $erro = "A senha deve conter entre 8 e 12 caracteres.";
        } else {
            $pass = strtoupper(md5($rawPass));
            $stmt = $connAccountServer->prepare("SELECT * FROM dbo.account_login WHERE name = ?");
            $stmt->execute([$user]);
            $account = $stmt->fetch();

            if ($account && $account['password'] === $pass) {
                $_SESSION['user'] = $user;
                header("Location: dashboard.php");
                exit;
            } else {
                $erro = "Usuário ou senha inválidos.";
            }
        }
    }

    if ($formType === 'register') {
        $user = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $rawPass = $_POST['password'] ?? '';

        if (strlen($rawPass) < 8 || strlen($rawPass) > 12) {
            $erro = "A senha deve conter entre 8 e 12 caracteres.";
        } else {
            $pass = strtoupper(md5($rawPass));

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
    }

}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login / Registro - Painel</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark-custom">

<div class="auth-container">
    <div class="auth-box shadow">
        <img src="assets/img/logo.png" alt="Logo" class="mb-3" style="width:120px;">

        <ul class="nav nav-tabs mb-3 justify-content-center">
            <li class="nav-item">
                <button class="nav-link active" id="login-tab">Login</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="register-tab">Registrar</button>
            </li>
        </ul>

        <div id="toast-container">
            <?php if ($erro): ?>
                <div class="toast-error"><?= $erro ?></div>
            <?php endif; ?>
            <?php if ($sucesso): ?>
                <div class="toast-success"><?= $sucesso ?></div>
            <?php endif; ?>
        </div>

        <div class="tab-content">
            <!-- LOGIN -->
            <div class="tab-pane active" id="tab-login">
                <form method="POST">
                    <input type="hidden" name="form_type" value="login">
                    <input type="text" name="username" class="form-control neon-input mb-2" placeholder="Usuário" required>
                    <input type="password" name="password" class="form-control neon-input mb-3" placeholder="Senha" required>
                    <button type="submit" class="btn neon-btn w-100">
                        <span class="btn-text">Entrar</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </form>
            </div>

            <!-- REGISTRO -->
            <div class="tab-pane" id="tab-register">
                <form method="POST">
                    <input type="hidden" name="form_type" value="register">
                    <input type="text" name="username" class="form-control neon-input mb-2" placeholder="Usuário" required>
                    <input type="email" name="email" class="form-control neon-input mb-2" placeholder="Email" required>
                    <input type="password" name="password" class="form-control neon-input mb-3" placeholder="Senha" required>
                    <button type="submit" class="btn btn-outline-light w-100">
                        <span class="btn-text">Registrar</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/scripts.js"></script>
</body>
</html>
