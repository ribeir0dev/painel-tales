<?php
// config/conexao.php

// Configurações do servidor ODBC
$accountServerDsn = 'odbc:Driver={SQL Server};Server=DESKTOP-4T656HQ\CT25;Database=AccountServer;';
$gameDbDsn = 'odbc:Driver={SQL Server};Server=DESKTOP-4T656HQ\CT25;Database=GameDB;';
$painelDsn = 'odbc:Driver={SQL Server};Server=DESKTOP-4T656HQ\CT25;Database=painel_db;';
$user = 'dbct25';
$password = 'contapw1';

// Conexões globais
try {
    $connAccountServer = new PDO($accountServerDsn, $user, $password);
    $connAccountServer->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com AccountServer: " . $e->getMessage());
}

try {
    $connGameDB = new PDO($gameDbDsn, $user, $password);
    $connGameDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com GameDB: " . $e->getMessage());
}

try {
    $connPainel = new PDO($painelDsn, $user, $password);
    $connPainel->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com painel_db: " . $e->getMessage());
}

?>


