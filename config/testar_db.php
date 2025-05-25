<?php
// config/testar_db.php

require_once 'conexao.php';

echo "<h2>Teste de Conexão com a Database</h2>";

// Testar tabela account_login
try {
    $stmt = $connAccountServer->query("SELECT TOP 1 name FROM dbo.account_login");
    $row = $stmt->fetch();
    echo "<p><strong>AccountServer:</strong> Conexão OK. Tabela account_login acessível. Exemplo: " . $row['name'] . "</p>";
} catch (PDOException $e) {
    echo "<p><strong>AccountServer:</strong> Erro ao acessar tabela account_login: " . $e->getMessage() . "</p>";
}

// Testar tabela character
try {
    $stmt = $connGameDB->query("SELECT TOP 1 cha_name FROM dbo.character");
    $row = $stmt->fetch();
    echo "<p><strong>GameDB:</strong> Conexão OK. Tabela character acessível. Exemplo: " . $row['cha_name'] . "</p>";
} catch (PDOException $e) {
    echo "<p><strong>GameDB:</strong> Erro ao acessar tabela character: " . $e->getMessage() . "</p>";
}
?>