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
    <link rel="stylesheet" href="../private/assets/css/main.css"/> <!--main.css-->
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
                    <h1 class="display-4 fw-bold text-dark mb-4">Gestão de Material <span class="text-primary">Strixhaven</span></h1>
                    <p class="lead text-secondary">TrueTech's innovative solution for medical equipment management. With this new platform your equipment will be organized like never before.</p>
                    <a href="#servicos" class="btn btn-primary btn-lg mt-3">Explorar Software</a>
                </div>
            </div>
        </div>
    </header>

    <!-- About Us Section -->
    <section id="quem-somos" class="container-texto-quem-somos" >
        <div class="container text-end">
            <div class=" ms-auto">
                <h2 class="col-lg-8 ms-auto quem-somos-texto-Title">Who are TrueTech ?</h2>
                <p class="col-lg-4 fs-5 quem-somos-texto ms-auto">
                    TrueTech Inc. is a leading company in the field of medical equipment management, providing inovative, reliable and efficient solutions to aid the work of our healthcare professionals.
                    <br>
                    With their new software, <span class="fst-italic" >Strixhaven</span>, they aim to revolutionize the way equipment is maintained and monitored. Using a key based system, they ensure that each professional doesn't need to see more than they need, simplyfying the interface and reducing the visual clutter, speeding up the workflow.
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
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="team-card card h-100 border-0 shadow-sm rounded-4 p-3 text-center strix-card-hover">                        
                        <div>
                            <img src="../private/assets/images/engineer_man.jpg" class="rounded-3 mb-3" width="200" height="200" alt="Foto de perfil de John Mariah">
                        </div>
                        <div class="card-body">
                            <h5 class="fw-bold mb-1">Alex Johnson</h5>
                            <p class="small text-primary fw-semibold text-uppercase">Biomedical Engineer </p>
                            <p class="card-text small text-muted">Especialista em manutenção de equipamentos de imagem diagnóstica.</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pb-3">
                            <a target="_blank" href="https://github.com/Phyloon" class="btn btn-outline-primary btn-sm rounded-pill px-4">Ver Perfil</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="team-card card h-100 border-0 shadow-sm rounded-4 p-3 text-center strix-card-hover">
                        <div>
                            <img src="../private/assets/images/doctor_dude_man.jpg" class="rounded-3 mb-3" width="200" height="200" alt="Foto de perfil de John Mariah">
                        </div>
                        <div class="card-body">
                            <h5 class="fw-bold mb-1">Sarah Chen</h5>
                            <p class="small text-primary fw-semibold text-uppercase">Lead Technician</p>
                            <p class="card-text small text-muted">Gestão de calibrações hospitalares e vistorias técnicas de segurança.</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pb-3">
                            <a target="_blank" href="https://github.com/Phyloon" class="btn btn-outline-primary btn-sm rounded-pill px-4">Ver Perfil</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="team-card card h-100 border-0 shadow-sm rounded-4 p-3 text-center strix-card-hover">
                        <div>
                            <img src="../private/assets/images/one_of_the_dudes.jpg" class="rounded-3 mb-3" width="200" height="200" alt="Foto de perfil de John Mariah">
                        </div>
                        <div class="card-body">
                            <h5 class="fw-bold mb-1">John Mariah</h5>
                            <p class="small text-primary fw-semibold text-uppercase">Lead Technician</p>
                            <p class="card-text small text-muted">Gestão de calibrações hospitalares e vistorias técnicas de segurança.</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pb-3">
                            <a target="_blank" href="https://github.com/Phyloon" class="btn btn-outline-primary btn-sm rounded-pill px-4">Ver Perfil</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FAQ Section with Bootstrap Accordion -->
    <section id="FAQ" class="container-texto-FAQ">
        <div class="container">
            <h2 class="display-5">Frequently asked questions</h2>
            <div class="accordion" id="accordionFAQ">
                <!-- Item 1 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                            Como acesso a área técnica?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                        <div class="accordion-body">
                            Utiliza o botão <strong>Login</strong> no topo direito e insere as tuas credenciais de técnico para aceder à gestão de manutenção.
                        </div>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                            O que significa a tag "OUT OF SERVICE"?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                        <div class="accordion-body">
                            Esta tag indica que o equipamento está em manutenção ou foi requisitado pela equipa técnica para análise.
                        </div>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                            Quais as vantagens de escolher este sistema?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                        <div class="accordion-body">
                            It's simply superior, skill issue.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="servicos" class=" bg-white shadow-sm container-texto-servicos">
        <div class="container">
            <h2 class="display-5 text-center mb-5">Serviços</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card_services">
                        <div class="card-body">
                            <h5 class="card_services_title fw-bold">Inventário em Tempo Real</h5>
                            <p class="card_services_text">Rastreio completo de dispositivos médicos, desde bombas de infusão a sistemas de diagnóstico por imagem.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card_services">
                        <div class="card-body">
                            <h5 class="card_services_title fw-bold">Manutenção Preventiva</h5>
                            <p class="card_services_text">Alertas automáticos para calibrações e vistorias técnicas obrigatórias.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card_services">
                        <div class="card-body">
                            <h5 class="card_services_title fw-bold">Gestão de Tickets</h5>
                            <p class="card_services_text">Submissão direta de avarias pelos técnicos e acompanhamento do estado de reparação.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card_services">
                        <div class="card-body">
                            <h5 class="card_services_title fw-bold">Relatórios Personalizados</h5>
                            <p class="card_services_text">Análise detalhada do desempenho dos equipamentos e histórico de manutenção.</p>
                        </div>
                    </div>
                </div>
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