<?php
$personagens = $connGameDB->prepare("
    SELECT cha_name, job, degree, map, credit 
    FROM dbo.character 
    WHERE accountid = (SELECT accountid FROM dbo.account WHERE name = ?)
");
$personagens->execute([$username]);
$chars = $personagens->fetchAll();
?>

<div class="card bg-panel p-3">
    <h5 class="text-white">Meus Personagens</h5>
    <?php if ($chars): ?>
        <ul class="list-group list-group-flush">
            <?php foreach ($chars as $char): ?>
                <li class="list-group-item bg-transparent text-light border-secondary">
                    <strong><?= $char['cha_name'] ?></strong> - <?= $char['job'] ?> | <?= $char['map'] ?> | Créditos: <?= $char['credit'] ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-light">Nenhum personagem encontrado.</p>
    <?php endif; ?>
</div>
