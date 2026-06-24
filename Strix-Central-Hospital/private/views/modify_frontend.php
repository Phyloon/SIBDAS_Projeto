<?php
// Load database configuration
require_once '../../config/config.php';
session_start();

// Variable to hold success/error messages
$msg = "";

// 1. PROCESS FORM SUBMISSION (updates hero, about, team, services, FAQs)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_all_frontpage') {
    try {
        $pdo->beginTransaction();

        // A. Update main text settings (hero and about)
        $hero_title    = $_POST['hero_title'];
        $hero_subtitle = $_POST['hero_subtitle'];
        $about_title   = $_POST['about_title'];
        $about_text    = $_POST['about_text'];

        $sqlSettings = "UPDATE landing_settings SET hero_title = ?, hero_subtitle = ?, about_title = ?, about_text = ? WHERE id = 1";
        $pdo->prepare($sqlSettings)->execute([$hero_title, $hero_subtitle, $about_title, $about_text]);

        // B. Update all team members (loop through each)
        if (isset($_POST['team'])) {
            $sqlTeam = "UPDATE landing_team SET nome = ?, cargo = ?, descricao = ?, imagem = ?, github = ? WHERE id = ?";
            $stmtTeam = $pdo->prepare($sqlTeam);
            foreach ($_POST['team'] as $id => $data) {
                $stmtTeam->execute([$data['nome'], $data['cargo'], $data['descricao'], $data['imagem'], $data['github'], $id]);
            }
        }

        // C. Update all services (loop through each)
        if (isset($_POST['service'])) {
            $sqlService = "UPDATE landing_services SET titulo = ?, descricao = ? WHERE id = ?";
            $stmtService = $pdo->prepare($sqlService);
            foreach ($_POST['service'] as $id => $data) {
                $stmtService->execute([$data['titulo'], $data['descricao'], $id]);
            }
        }

        // D. Update all FAQs (loop through each)
        if (isset($_POST['faq'])) {
            $sqlFaq = "UPDATE landing_faq SET pergunta = ?, resposta = ? WHERE id = ?";
            $stmtFaq = $pdo->prepare($sqlFaq);
            foreach ($_POST['faq'] as $id => $data) {
                $stmtFaq->execute([$data['pergunta'], $data['resposta'], $id]);
            }
        }

        $pdo->commit();
        $msg = "<div class='alert alert-success'>Toda a Frontpage foi atualizada com sucesso!</div>";
    } catch (Exception $e) {
        $pdo->rollBack();
        $msg = "<div class='alert alert-danger'>Erro ao atualizar os dados: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

// 2. FETCH LATEST DATA FROM DATABASE TO PRE-FILL THE FORM
$settings     = $pdo->query("SELECT * FROM landing_settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
$team_members = $pdo->query("SELECT * FROM landing_team")->fetchAll(PDO::FETCH_ASSOC);
$faqs         = $pdo->query("SELECT * FROM landing_faq")->fetchAll(PDO::FETCH_ASSOC);
$services     = $pdo->query("SELECT * FROM landing_services")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="d-flex">
    <?php include '../includes/nav.php'; ?>
    <div id="content" class="w-100">
        <?php include '../includes/topbar.php'; ?>
        <div class="container-fluid p-4">
            <!-- Page header and live view link -->
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <h2>Frontpage Management Control</h2>
                <a href="../../public/index.php" target="_blank" class="btn btn-outline-secondary btn-sm">View Live Page</a>
            </div>

            <!-- Display success/error messages -->
            <?= $msg ?>

            <!-- FORM: Updates all frontpage content at once -->
            <form method="POST" class="mb-5">
                <input type="hidden" name="action" value="update_all_frontpage">

                <!-- SECTION: Hero Header Settings -->
                <h4 class="text-primary border-bottom pb-2">Hero Header Settings</h4>
                <div class="mb-3">
                    <label class="form-label fw-bold">Hero Title</label>
                    <input type="text" name="hero_title" class="form-control" value="<?= htmlspecialchars($settings['hero_title'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Hero Subtitle</label>
                    <textarea name="hero_subtitle" class="form-control" rows="3" required><?= htmlspecialchars($settings['hero_subtitle'] ?? '') ?></textarea>
                </div>

                <!-- SECTION: About Us Text Block -->
                <h4 class="text-primary border-bottom pb-2 mt-4">About Us Text Block</h4>
                <div class="mb-3">
                    <label class="form-label fw-bold">About Title</label>
                    <input type="text" name="about_title" class="form-control" value="<?= htmlspecialchars($settings['about_title'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">About Content Narrative</label>
                    <textarea name="about_text" class="form-control" rows="5" required><?= htmlspecialchars($settings['about_text'] ?? '') ?></textarea>
                </div>

                <hr class="my-5">

                <!-- SECTION: Team Members (loop through each member) -->
                <h4 class="text-primary border-bottom pb-2 mt-4">Secção: Nossa Equipa</h4>
                <div class="row g-4 mb-4">
                    <?php foreach ($team_members as $index => $member): ?>
                        <div class="col-md-4">
                            <div class="p-3 bg-light border shadow-sm" style="border-radius: 12px; height: 100%;">
                                <span class="badge bg-primary mb-2">Membro #<?= $index + 1 ?></span>

                                <div class="mb-2">
                                    <label class="form-label small fw-bold text-muted">Nome</label>
                                    <input type="text" name="team[<?= $member['id'] ?>][nome]" class="form-control" value="<?= htmlspecialchars($member['nome']) ?>" required style="border-radius:8px;">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold text-muted">Cargo</label>
                                    <input type="text" name="team[<?= $member['id'] ?>][cargo]" class="form-control" value="<?= htmlspecialchars($member['cargo']) ?>" required style="border-radius:8px;">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold text-muted">Caminho da Imagem</label>
                                    <input type="text" name="team[<?= $member['id'] ?>][imagem]" class="form-control text-muted small" value="<?= htmlspecialchars($member['imagem']) ?>" required style="border-radius:8px;">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold text-muted">GitHub URL</label>
                                    <input type="text" name="team[<?= $member['id'] ?>][github]" class="form-control text-muted small" value="<?= htmlspecialchars($member['github']) ?>" style="border-radius:8px;">
                                </div>
                                <div class="mb-0">
                                    <label class="form-label small fw-bold text-muted">Descrição / Especialidade</label>
                                    <textarea name="team[<?= $member['id'] ?>][descricao]" class="form-control" rows="3" required style="border-radius:8px;"><?= htmlspecialchars($member['descricao']) ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <hr class="my-5">

                <!-- SECTION: Services (loop through each service) -->
                <h4 class="text-primary border-bottom pb-2 mt-5">Secção: Serviços</h4>
                <div class="row g-3 mb-4">
                    <?php foreach ($services as $index => $service): ?>
                        <div class="col-md-6">
                            <div class="p-3 bg-light border shadow-sm" style="border-radius: 12px; height: 100%;">
                                <span class="badge bg-primary mb-2">Card de Serviço #<?= $index + 1 ?></span>

                                <div class="mb-2">
                                    <label class="form-label small fw-bold text-muted">Título do Serviço</label>
                                    <input type="text" name="service[<?= $service['id'] ?>][titulo]" class="form-control" value="<?= htmlspecialchars($service['titulo']) ?>" required style="border-radius:8px;">
                                </div>
                                <div class="mb-0">
                                    <label class="form-label small fw-bold text-muted">Descrição</label>
                                    <textarea name="service[<?= $service['id'] ?>][descricao]" class="form-control" rows="3" required style="border-radius:8px;"><?= htmlspecialchars($service['descricao']) ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <hr class="my-5">

                <!-- SECTION: FAQs (loop through each FAQ) -->
                <h4 class="text-primary border-bottom pb-2 mt-5">Secção: FAQ (Perguntas Frequentes)</h4>
                <div class="row g-3 mb-4">
                    <?php foreach ($faqs as $index => $faq): ?>
                        <div class="col-12 p-3 bg-light border shadow-sm mb-2" style="border-radius: 12px;">
                            <span class="badge bg-primary mb-2">Pergunta #<?= $index + 1 ?></span>

                            <div class="mb-2">
                                <label class="form-label small fw-bold text-muted">Pergunta</label>
                                <input type="text" name="faq[<?= $faq['id'] ?>][pergunta]" class="form-control" value="<?= htmlspecialchars($faq['pergunta']) ?>" required style="border-radius:8px;">
                            </div>
                            <div class="mb-0">
                                <label class="form-label small fw-bold text-muted">Resposta</label>
                                <textarea name="faq[<?= $faq['id'] ?>][resposta]" class="form-control" rows="2" required style="border-radius:8px;"><?= htmlspecialchars($faq['resposta']) ?></textarea>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-success w-100 btn-lg mt-4" style="border-radius:8px; font-weight:bold;">
                    Save All Changes
                </button>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>