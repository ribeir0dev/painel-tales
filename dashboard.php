<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>
  
    <!-- Conteúdo -->
    <main class="flex-grow-1 p-4">
        <h4 class="text-white mb-4">Olá, <?= htmlspecialchars($user) ?>!</h4>

        <div class="row g-4">
            <!-- PERSONAGENS (com visual customizado) -->
            <div class="col-12">
                <div class="card panel-glass p-3">
                    <h5 class="text-white mb-4"><i class="fas fa-users me-2"></i>Meus Personagens</h5>
                    <div class="row g-4">
                        <?php
                        $stmt = $connGameDB->prepare("
                            SELECT cha_name, job, degree 
                            FROM dbo.character 
                            WHERE act_id = (
                                SELECT act_id FROM dbo.account WHERE act_name LIKE ?
                            )
                        ");
                        $stmt->execute([$user]);
                        $chars = $stmt->fetchAll();

                        $total = count($chars);
                        $max = 3;

                        foreach ($chars as $char):
                            $job = strtolower(preg_replace('/\s+/', '-', $char['job']));
                            $imgPath = "assets/img/jobs/{$job}.png";
                        ?>
                           <div class="col-md-4">
                                <div class="char-box text-center p-3 d-flex flex-column align-items-center justify-content-between" style="min-height: 250px;">
                                    <img src="<?= file_exists($imgPath) ? $imgPath : 'assets/img/jobs/default.png' ?>" alt="<?= $char['job'] ?>" class="job-img mb-2">
                                    <h6 class="text-green font-weight-bolder"><?= htmlspecialchars($char['cha_name']) ?></h6>
                                    <p class="text-white mb-0">Classe: <?= htmlspecialchars($char['job']) ?></p>
                                    <span class="badge badge-bg mt-1 mb-2">Nível <?= $char['degree'] ?></span>
                                    <a href="personagem.php?cha_name=<?= urlencode($char['cha_name']) ?>" class="btn btn-light btn-sm w-50 mt-auto">+ Detalhes</a>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php for ($i = 0; $i < $max - $total; $i++): ?>
                            <div class="col-md-4">
                                <div class="char-box text-center p-4 d-flex flex-column justify-content-center align-items-center opacity-50">
                                    <img src="assets/img/jobs/default.png" alt="Slot Vazio" class="job-img mb-2">
                                    <h6 class="text-white-50">Slot vazio</h6>
                                    <p class="text-white-50">Nenhum personagem</p>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 mb-4">
            <!-- Total de Doações -->
            <div class="col-md-6 col-lg-3">
                <div class="card card-destaque panel-glass p-3 text-center">
                    <p class="text-topo mb-1">Total de Doações</p>
                    <h4 class="text-green">R$ 0,00</h4> <!-- Simulado -->
                </div>
            </div>

            <!-- Total de IMPS -->
            <div class="col-md-6 col-lg-3">
                <?php
                $stmt = $connGameDB->prepare("
                    SELECT SUM(credit) as total_credit 
                    FROM dbo.character 
                    WHERE act_id = (
                        SELECT act_id FROM dbo.account WHERE act_name LIKE ?
                    )
                ");
                $stmt->execute([$user]);
                $result = $stmt->fetch();
                $totalIMPS = $result['total_credit'] ?? 0;
                ?>
                <div class="card panel-glass p-3 text-center">
                    <p class="text-topo mb-1">Total de IMPS</p>
                    <h4 class="text-green"><?= $totalIMPS ?></h4>
                </div>
            </div>

            <!-- Últimas Doações -->
            <div class="col-md-12 col-lg-6">
                <div class="card panel-glass p-3">
                    <h6 class="text-topo mb-3"><i class="fas fa-clock me-2"></i>Últimas Doações</h6>
                    <table class="table table-sm table-white table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Valor</th>
                                <th>Método</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Simulação temporária -->
                            <tr>
                                <td>21/05/2025</td>
                                <td>R$ 25,00</td>
                                <td>Pix</td>
                            </tr>
                            <tr>
                                <td>15/05/2025</td>
                                <td>R$ 50,00</td>
                                <td>Pix</td>
                            </tr>
                            <tr>
                                <td>03/05/2025</td>
                                <td>R$ 10,00</td>
                                <td>Cartão</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </main>

<?php include 'includes/footer.php'; ?>