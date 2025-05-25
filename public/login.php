<?php require_once '../config/conexao_user.php';

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel - Login & Registro</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark-custom">

<div class="auth-container">
    <div class="auth-box shadow">
        <img src="../assets/img/logo.png" alt="Logo" class="mb-3" style="width:120px;">

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
            <div class="tab-pane active" id="login-form">
                <form method="POST" id="form-login">
                    <input type="hidden" name="form_type" value="login">
                    <input type="text" name="username" class="form-control neon-input mb-2" placeholder="Usuário" required>
                    <input type="password" name="password" class="form-control neon-input mb-3" placeholder="Senha" required>
                    <button type="submit" class="btn neon-btn w-100">
                        <span class="btn-text">Entrar</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </form>
            </div>

            <div class="tab-pane" id="register-form">
                <form method="POST" id="form-register">
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

<script src="../assets/js/scripts.js"></script>
</body>
</html>
