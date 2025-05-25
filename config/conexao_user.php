<?php
session_start();
require_once '../config/conexao.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formType = $_POST['form_type'] ?? '';

    if ($formType === 'login') {
        $user = $_POST['username'] ?? '';
        $pass = md5($_POST['password'] ?? '');

        $stmt = $connAccountServer->prepare("SELECT * FROM dbo.account_login WHERE name = ?");
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

    if ($formType === 'register') {
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
}
?>