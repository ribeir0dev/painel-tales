<?php
require_once 'config/conexao.php';

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$emailNovo = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';
$senhaConfirmar = $_POST['senha_confirmar'] ?? '';
$erros = [];

// Buscar e-mail atual
$stmt = $connAccountServer->prepare("SELECT email FROM dbo.account_login WHERE name = ?");
$stmt->execute([$user]);
$conta = $stmt->fetch();
$emailAtual = $conta['email'] ?? '';

if (!$emailNovo || !filter_var($emailNovo, FILTER_VALIDATE_EMAIL)) {
    $erros[] = "Email novo inválido.";
}

if ($senha && (strlen($senha) < 8 || strlen($senha) > 12)) {
    $erros[] = "Senha deve conter entre 8 e 12 caracteres.";
}

if ($senha && $senha !== $senhaConfirmar) {
    $erros[] = "As senhas não coincidem.";
}

if (empty($erros)) {
    // Gerar códigos de verificação
    $codigoAtual = strtoupper(substr(md5(uniqid()), 0, 6));
    $codigoNovo = strtoupper(substr(md5(uniqid(true)), 0, 6));

    // Salvar solicitação no painel
    $ins = $connPainel->prepare("
        INSERT INTO painel_troca_email (account_name, email_atual, email_novo, codigo_atual, codigo_novo)
        VALUES (?, ?, ?, ?, ?)
    ");
    $ins->execute([$user, $emailAtual, $emailNovo, $codigoAtual, $codigoNovo]);

    // Enviar email para o e-mail atual
    $linkAtual = "http://seusite.com/verificar_email.php?codigo=$codigoAtual";
    mail($emailAtual, "Confirme alteração de email", 
        "Olá $user,\n\nClique no link para confirmar a troca de email:\n$linkAtual\n\nSe não solicitou, ignore.",
        "From: painel@seusite.com");

    // Enviar email para o novo email
    $linkNovo = "http://seusite.com/verificar_email.php?codigo=$codigoNovo";
    mail($emailNovo, "Confirme seu novo email", 
        "Olá $user,\n\nClique no link para validar este novo e-mail:\n$linkNovo\n\nSe não solicitou, ignore.",
        "From: painel@seusite.com");

    echo "<p style='color: white; padding: 20px;'>Solicitação enviada. Confirme nos dois e-mails para concluir a troca.</p>";
} else {
    foreach ($erros as $e) {
        echo "<p style='color: red; padding: 5px;'>$e</p>";
    }
    echo "<p><a href='conta.php' style='color: lightblue;'>Voltar</a></p>";
}
?>
