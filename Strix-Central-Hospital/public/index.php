<?php
require_once '../config/config.php';

// Fetch main settings
$settings = $pdo->query("SELECT * FROM landing_settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);

// Fetch all team members, FAQs, and services
$team_members = $pdo->query("SELECT * FROM landing_team")->fetchAll(PDO::FETCH_ASSOC);
$faqs         = $pdo->query("SELECT * FROM landing_faq")->fetchAll(PDO::FETCH_ASSOC);
$services     = $pdo->query("SELECT * FROM landing_services")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrueTech</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Habibi&display=swap" rel="stylesheet">

    <!--Favicon-->
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/png" />
    <link rel="stylesheet" href="../private/assets/css/1240863_1.css"/> <!--main.css-->
    <link rel="stylesheet" href="../private/assets/css/bootstrap.min.css"/> <!--bootstrap.min.css-->
    <link rel="stylesheet" href="../private/assets/css/bootstrap-icons.css"/> <!--bootstrap-icons.css-->
</head>

<body class="body">

    <!--TOP OF SCREEN NAVBAR-->
    <nav class="bng-navbar">

        <!--LOGO AND NAME-->
        <a href="#" id="logo-scroll-top" class="navbar-brand d-flex align-items-center">
            <div class="logo-icon me-2"></div>
            <span class="logo-text fw-bold fs-4">True<span class="text-primary">Tech</span></span>
        </a>

        <!--NAVIGATION BUTTONS-->
        <div class="container-navegacao">
            <a href="#quem-somos">Quem Somos</a>
            <a href="#nossa-equipa">Nossa Equipa</a>
            <a href="#FAQ">FAQ</a>
            <a href="#servicos">Serviços</a>
        </div>

        <!--APPEARANCE AND LOGIN-->
        <div class="nav-cliente">
            <div class="dropdown ms-3">
                <button class="btn btn-outline-dark dropdown-toggle nav-cliente" type="button" id="bd-theme" data-bs-toggle="dropdown" aria-expanded="false">
                    Aparência
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme">
                    <li>
                    <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light">
                        Light
                    </button>
                    </li>
                    <li>
                    <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark">
                        Dark
                    </button>
                    </li>
                </ul>
            </div>
            <a href="login.php" target="_bl">Login</a>         
        </div>
    </nav>


    <!--PAGE CONTENT-->


    <!-- Hero Section -->
    <header id="inicio" class="hero-section">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold text-dark mb-4"><?= htmlspecialchars($settings['hero_title']) ?></h1>
                    
                    <p class="lead text-secondary"><?= htmlspecialchars($settings['hero_subtitle']) ?></p>
                    
                    <a href="#servicos" class="btn btn-primary btn-lg mt-3">Explorar Software</a>
                </div>
            </div>
        </div>
    </header>

    <!-- About Us Section -->
    <section id="quem-somos" class="container-texto-quem-somos" >
        <div class="container text-end">
            <div class=" ms-auto">
                <h2 class="col-lg-8 ms-auto quem-somos-texto-Title"><?= htmlspecialchars($settings['about_title']) ?></h2>
                
                <p class="col-lg-4 fs-5 quem-somos-texto ms-auto">
                    <?= nl2br(htmlspecialchars($settings['about_text'])) ?>
                </p>   
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section id="nossa-equipa" class="container-texto-generico">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold" style="color: #007BFF;">Nossa Equipa</h2>
                <p class="lead text-secondary">Meet our specialized team of biomedical engineers and technicians.<br>These are the professionals who make this software as reliable and user-friendly as it is.</p>
            </div>

            <div class="row g-4 justify-content-center">
                <?php foreach ($team_members as $member): ?>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="team-card card h-100 border-0 shadow-sm rounded-4 p-3 text-center strix-card-hover">                        
                            <div>
                                <img src="<?= htmlspecialchars($member['imagem']) ?>" class="rounded-3 mb-3" width="200" height="200" alt="Foto de perfil">
                            </div>
                            <div class="card-body">
                                <h5 class="fw-bold mb-1"><?= htmlspecialchars($member['nome']) ?></h5>
                                <p class="small text-primary fw-semibold text-uppercase"><?= htmlspecialchars($member['cargo']) ?></p>
                                <p class="card-text small text-muted"><?= htmlspecialchars($member['descricao']) ?></p>
                            </div>
                            <div class="card-footer bg-transparent border-0 pb-3">
                                <a target="_blank" href="<?= htmlspecialchars($member['github']) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-4">Ver Perfil</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- FAQ Section with Bootstrap Accordion -->
    <section id="FAQ" class="container-texto-FAQ">
        <div class="container">
            <h2 class="display-5">Frequently asked questions</h2>
            <div class="accordion" id="accordionFAQ">
                <?php foreach ($faqs as $index => $faq): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?= $index ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>">
                                <?= htmlspecialchars($faq['pergunta']) ?>
                            </button>
                        </h2>
                        <div id="collapse<?= $index ?>" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                <?= nl2br(htmlspecialchars($faq['resposta'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="servicos" class="bg-white shadow-sm container-texto-servicos">
        <div class="container">
            <h2 class="display-5 text-center mb-5">Serviços</h2>
            <div class="row g-4">
                <?php foreach ($services as $service): ?>
                    <div class="col-md-3">
                        <div class="card_services">
                            <div class="card-body">
                                <h5 class="card_services_title fw-bold"><?= htmlspecialchars($service['titulo']) ?></h5>
                                <p class="card_services_text"><?= htmlspecialchars($service['descricao']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>


    <!--FOOTER-->


    <footer class="footer">
        <div class="container d-flex justify-content-between align-items-center py-2">
            <div class="footer-brand">
                <span class="habibi-regular text-white">TrueTech Inc.</span>
            </div>
            
            <div class="footer-contacts">
                <a href="mailto:contato@truetech.com">contato@truetech.com</a>
                <span class="mx-4 text-white">|</span>
                <span>+351 123 456 789</span>
                <span class="mx-3 text-white">|</span>
                <span>Porto, Portugal</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS (Necessário para a sanfona funcionar) -->
    <script src="../private/assets/js/1240863.js"></script>
    <script src="../private/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>