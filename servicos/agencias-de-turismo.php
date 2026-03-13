<?php
require_once '../admin/config.php';
$tipo_servico = 'agencia';

$page_title = "Agências de Turismo - Caxias Tem Turismo";
$page_description = "Encontre as melhores agências de turismo em Caxias do Sul para planejar sua viagem.";
$is_subfolder = true;
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>
<body>
  <!-- Barra de Progresso de Rolagem -->
  <div class="scroll-progress" id="scrollProgress"></div>
  
  <!-- Toast Notification -->
  <div class="toast-notification" id="toastNotification">
    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="margin-right: 8px;">
      <circle cx="10" cy="10" r="9" stroke="white" stroke-width="2" fill="none"/>
      <path d="M6 10L9 13L14 7" stroke="white" stroke-width="2" stroke-linecap="round"/>
    </svg>
    <span id="toastMessage">Mensagem</span>
  </div>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb" data-aos="fade-right">
                <li class="breadcrumb-item"><a href="../index.php">Início</a></li>
                <li class="breadcrumb-item"><a href="../index.php#servicos">Serviços</a></li>
                <li class="breadcrumb-item active" aria-current="page">Agências de Turismo</li>
            </ol>
        </div>
    </nav>

    <header class="hero" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('../img/img1.png'); height: 50vh;" data-aos="fade-in">
        <div class="container">
            <h1 style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Agências de Turismo em Caxias do Sul</h1>
            <p class="lead" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Planeje sua viagem com especialistas locais e descubra roteiros exclusivos.</p>
        </div>
    </header>

    <main class="content-section">
        <div class="container" data-aos="fade-up">
            <h2 class="mb-5">🏢 Agências Cadastradas</h2>
            <div class="row g-4">
                <!-- Prestadores via banco de dados -->
                <?php include '_prestadores_bloco.php'; ?>
            </div>
        </div>
    </main>

    <section id="contato" class="content-section bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2 class="text-center mb-4">📬 Fale Conosco</h2>
                    <div class="contact-form">
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Mensagem</label>
                                <textarea class="form-control" id="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require_once '../includes/footer.php'; ?>
</body>
</html>
