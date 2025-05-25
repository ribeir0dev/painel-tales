<?php
require_once 'config/conexao.php';

$codigo = strtoupper(trim($_GET['codigo'] ?? ''));

if (!$codigo) {
    echo "<p style='color: white; padding: 20px;'>Código inválido.</p>";
    exit;
}

// Buscar solicitação de troca
$stmt = $connPainel->prepare("
    SELECT * FROM painel_troca_email 
    WHERE codigo_atual = ? OR codigo_novo = ?
");
$stmt->execute([$codigo, $codigo]);
$solicitacao = $stmt->fetch();

if (!$solicitacao) {
    echo "<p style='color: white; padding: 20px;'>Código não encontrado.</p>";
    exit;
}

// Marcar validação
if ($codigo === $solicitacao['codigo_atual']) {
    $connPainel->prepare("UPDATE painel_troca_email SET validado_atual = 1 WHERE id = ?")
               ->execute([$solicitacao['id']]);
    echo "<p style='color: lightgreen; padding: 20px;'>E-mail atual confirmado com sucesso.</p>";
}
if ($codigo === $solicitacao['codigo_novo']) {
    $connPainel->prepare("UPDATE painel_troca_email SET validado_novo = 1 WHERE id = ?")
               ->execute([$solicitacao['id']]);
    echo "<p style='color: lightgreen; padding: 20px;'>Novo e-mail validado com sucesso.</p>";
}

// Revalidar status
$stmtCheck = $connPainel->prepare("SELECT * FROM painel_troca_email WHERE id = ?");
$stmtCheck->execute([$solicitacao['id']]);
$dados = $stmtCheck->fetch();

if ($dados['validado_atual'] && $dados['validado_novo']) {
    // Atualizar e-mail na AccountServer
    $atualiza = $connAccountServer->prepare("
        UPDATE dbo.account_login SET email = ? WHERE name = ?
    ");
    $atualiza->execute([$dados['email_novo'], $dados['account_name']]);

    // Registrar log da operação
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Desconhecido';
    $descricao = "E-mail alterado de {$dados['email_atual']} para {$dados['email_novo']}";
    $log = $connPainel->prepare("
        INSERT INTO painel_logs (account_name, tipo, descricao, ip_origem)
        VALUES (?, 'Troca de Email', ?, ?)
    ");
    $log->execute([$dados['account_name'], $descricao, $ip]);

    // Excluir solicitação
    $connPainel->prepare("DELETE FROM painel_troca_email WHERE id = ?")
               ->execute([$dados['id']]);

    echo "<p style='color: cyan; padding: 20px;'>E-mail alterado com sucesso!</p>";
    echo "<p><a href='login.php' style='color: lightblue;'>Voltar ao painel</a></p>";
}
?>
