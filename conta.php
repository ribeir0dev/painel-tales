<?php
include 'includes/header.php';
include 'includes/nav.php';

// Buscar dados da conta do painel_db
$stmtPainel = $connPainel->prepare("SELECT nome, genero FROM painel_users WHERE account_name = ?");
$stmtPainel->execute([$user]);
$painelData = $stmtPainel->fetch();

// Criar entrada se ainda não existir
if (!$painelData) {
    $criar = $connPainel->prepare("INSERT INTO painel_users (account_name, nome, genero) VALUES (?, '', 'Outro')");
    $criar->execute([$user]);
    $painelData = ['nome' => '', 'genero' => 'Outro'];
}

// Buscar dados da conta do servidor
$stmt = $connAccountServer->prepare("
    SELECT name, email, 
           DATEDIFF(SECOND, '1970-01-01', enable_login_time) AS created_at,
           last_login_ip
    FROM dbo.account_login 
    WHERE name = ?
");
$stmt->execute([$user]);
$conta = $stmt->fetch();
?>

<main class="flex-grow-1 p-4">
    <h4 class="text-white mb-4">Minha Conta</h4>

    <div class="row g-4">

        <!-- PAINEL 1: MEUS DADOS -->
        <div class="col-md-6">
            <div class="card panel-glass p-4">
                <h5 class="text-white mb-3"><i class="fas fa-user me-2"></i>Meus Dados</h5>

                <form action="conta_salvar.php" method="POST">
                    <label class="text-white mb-3">Login</label>
                    <p class="text-white no-change-input"><?= htmlspecialchars($conta['name']) ?></p>

                    <label class="text-white mb-3">E-mail</label>
                    <p class="text-white no-change-input"><?= htmlspecialchars($conta['email']) ?></p>

                    <label class="text-white mb-3">Nome</label>
                    <input type="text" name="nome" class="form-control neon-input mb-2"
                           value="<?= htmlspecialchars($painelData['nome']) ?>" maxlength="100">

                    <label class="text-white mb-3">Gênero</label>
                    <select name="genero" class="form-control neon-input mb-3">
                        <option <?= $painelData['genero'] == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                        <option <?= $painelData['genero'] == 'Feminino' ? 'selected' : '' ?>>Feminino</option>
                        <option <?= $painelData['genero'] == 'Outro' ? 'selected' : '' ?>>Outro</option>
                    </select>

                    <button type="submit" class="btn neon-btn">Salvar Dados</button>
                </form>
            </div>
        </div>

        <!-- PAINEL 2: ALTERAR EMAIL / SENHA -->
        <div class="col-md-6">
            <div class="card panel-glass p-4">
                <h5 class="text-white mb-3"><i class="fas fa-lock me-2"></i>Alterar Email / Senha</h5>
                <form action="alterar_dados.php" method="POST">
                    <label class="text-white mb-3">Novo Email</label>
                    <input type="email" name="email" class="form-control neon-input mb-2">

                    <label class="text-white mb-3">Nova Senha</label>
                    <input type="password" name="senha" class="form-control neon-input mb-2" minlength="8" maxlength="12">

                    <label class="text-white mb-3">Confirmar Senha</label>
                    <input type="password" name="senha_confirmar" class="form-control neon-input mb-3" minlength="8" maxlength="12">

                    <button type="submit" class="btn neon-btn">Atualizar</button>
                </form>
            </div>
        </div>

    </div>
</main>

<?php include 'includes/footer.php'; ?>
