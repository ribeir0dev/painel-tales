<?php
session_start();
require_once 'config/conexao.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$charName = $_GET['nome'] ?? '';

if (empty($charName)) {
    echo "<p style='color: white; padding: 20px;'>Personagem não especificado.</p>";
    exit;
}

// Buscar personagem da conta do jogador logado
$stmt = $connGameDB->prepare("
    SELECT cha_name, job, degree, map, credit 
    FROM dbo.character 
    WHERE cha_name = ? AND act_id = (
        SELECT act_id FROM dbo.account WHERE act_name LIKE ?
    )
");
$stmt->execute([$charName, $user]);
$char = $stmt->fetch();

if (!$char) {
    echo "<p style='color: white; padding: 20px;'>Personagem não encontrado ou não pertence à sua conta.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($char['cha_name']) ?> - Detalhes do Personagem</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="dashboard-dark">

<div class="container py-4">
    <a href="dashboard.php" class="btn btn-outline-light btn-sm mb-3">&larr; Voltar</a>

    <div class="card panel-glass p-4">
        <h3 class="text-white mb-3"><?= htmlspecialchars($char['cha_name']) ?></h3>

        <div class="row">
            <div class="col-md-4 text-center">
                <?php
                $job = strtolower(preg_replace('/\s+/', '-', $char['job']));
                $imgPath = "assets/img/jobs/{$job}.png";
                ?>
                <img src="<?= file_exists($imgPath) ? $imgPath : 'assets/img/jobs/default.png' ?>" alt="<?= $char['job'] ?>" class="job-img mb-3">
            </div>
            <div class="col-md-8">
                <p class="text-light mb-2"><strong>Classe:</strong> <?= htmlspecialchars($char['job']) ?></p>
                <p class="text-light mb-2"><strong>Nível:</strong> <?= $char['degree'] ?></p>
                <p class="text-light mb-2"><strong>Mapa Atual:</strong> <?= htmlspecialchars($char['map']) ?></p>
                <p class="text-light"><strong>Créditos:</strong> <?= $char['credit'] ?></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
