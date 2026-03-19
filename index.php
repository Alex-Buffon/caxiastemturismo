<?php
require_once 'admin/config.php';

$opcoes = [];
$res_opcoes = $conn->query("SELECT chave, valor FROM opcoes_site");
if($res_opcoes) {
    while($row = $res_opcoes->fetch_assoc()) { 
        $opcoes[$row['chave']] = $row['valor']; 
    }
}

$res_banners = $conn->query("SELECT * FROM banners ORDER BY ordem ASC");
$res_destinos = $conn->query("SELECT * FROM destinos ORDER BY ordem ASC");
$res_roteiros = $conn->query("SELECT * FROM destinos ORDER BY ordem ASC LIMIT 3");



$res_testimonials = $conn->query("SELECT * FROM depoimentos WHERE status = 'approved' ORDER BY aprovado_em DESC, criado_em DESC LIMIT 6");

$testimonial_feedback = null;
if (isset($_GET['testimonial_status'])) {
    if ($_GET['testimonial_status'] === 'success') {
        $testimonial_feedback = [
            'type' => 'success',
            'text' => 'Obrigado! Seu depoimento foi enviado e será exibido após aprovação.'
        ];
    } elseif ($_GET['testimonial_status'] === 'error') {
        $testimonial_feedback = [
            'type' => 'danger',
            'text' => isset($_GET['msg']) ? urldecode($_GET['msg']) : 'Ocorreu um erro ao enviar seu depoimento.'
        ];
    }
}

$page_title = $opcoes['site_titulo'] ?? "Caxias Tem Turismo - Roteiros, Gastronomia e Natureza na Serra Gaúcha";
$page_description = $opcoes['site_descricao'] ?? "Descubra o melhor do turismo em Caxias do Sul. Explore roteiros de charme, gastronomia típica italiana, vinícolas, e as belezas naturais da Serra Gaúcha.";

// --- SEO Avançado e Web Core Vitals ---

// 1. Preload da imagem LCP (Primeiro Banner) para carregar instantaneamente
$primeiro_banner = "img1.png";
if (isset($res_banners) && $res_banners->num_rows > 0) {
    $res_banners->data_seek(0);
    $fb = $res_banners->fetch_assoc();
    if (!empty($fb['imagem'])) $primeiro_banner = $fb['imagem'];
    $res_banners->data_seek(0); // reset pointer
}
$extra_head = '<link rel="preload" as="image" href="img/' . htmlspecialchars($primeiro_banner) . '">';

// 2. Schema AggregateRating (Para o Google exibir 5 estrelas nos resultados)
$total_avaliacoes = 0;
$soma_avaliacoes = 0;
if (isset($res_testimonials) && $res_testimonials->num_rows > 0) {
    $res_testimonials->data_seek(0);
    while($t = $res_testimonials->fetch_assoc()) {
        if (!empty($t['nota'])) {
            $total_avaliacoes++;
            $soma_avaliacoes += (int) $t['nota'];
        }
    }
    $res_testimonials->data_seek(0);
}

if ($total_avaliacoes > 0) {
    $media_avaliacoes = round($soma_avaliacoes / $total_avaliacoes, 1);
    $schema_aggregate_rating = json_encode([
        "@type" => "AggregateRating",
        "ratingValue" => (string)$media_avaliacoes,
        "reviewCount" => (string)$total_avaliacoes,
        "bestRating" => "5",
        "worstRating" => "1"
    ], JSON_UNESCAPED_UNICODE);
}

require_once 'includes/header.php';
?>
<body>

<?php require_once 'includes/navbar.php'; ?>

<main>
    <h1 class="visually-hidden"><?php echo $page_title; ?></h1>
    <!-- SEÇÃO HERO COM CAROUSEL -->
    <section id="inicio">
        <header class="hero">
            <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
                <div class="carousel-inner">
                    <?php 
                    $first = true;
                    if($res_banners && $res_banners->num_rows > 0): 
                        while($b = $res_banners->fetch_assoc()): 
                    ?>
                    <div class="carousel-item <?php echo $first ? 'active' : ''; ?>" 
                         style="background-image: url('img/<?php echo htmlspecialchars($b['imagem']); ?>');"
                         role="img" 
                         aria-label="<?php echo htmlspecialchars(!empty($b['alt_text']) ? $b['alt_text'] : $b['titulo']); ?>">
                        <div class="container">
                            <h2 class="h1"><?php echo htmlspecialchars($b['titulo']); ?></h2>
                            <p class="lead"><?php echo htmlspecialchars($b['subtitulo'] ?? ''); ?></p>
                            <div class="hero-buttons">
                                <a href="<?php echo !empty($b['link']) ? htmlspecialchars($b['link']) : '#roteiros'; ?>" class="btn btn-outline-light btn-lg">Explorar</a>
                            </div>
                        </div>
                    </div>
                    <?php 
                        $first = false;
                        endwhile; 
                    else:
                    ?>
                    <div class="carousel-item active" style="background-image: url('img/img1.png');">
                        <div class="container">
                            <h2 class="h1">Bem-vindo a Caxias do Sul</h2>
                            <p class="lead">Explore o melhor da Serra Gaúcha.</p>
                            <div class="hero-buttons">
                                <a href="#roteiros" class="btn btn-outline-light btn-lg">Explorar</a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Controles do Carousel -->
                <?php if($res_banners && $res_banners->num_rows > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
                <div class="carousel-indicators">
                    <?php 
                    $total_banners = $res_banners->num_rows;
                    for($idx = 0; $idx < $total_banners; $idx++):
                    ?>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?php echo $idx; ?>" class="<?php echo $idx === 0 ? 'active' : ''; ?>" aria-label="Slide <?php echo $idx + 1; ?>"></button>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            </div>
        </header>
    </section>

    <!-- SEÇÃO DE ROTEIROS -->
    <section id="roteiros" class="content-section bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto" data-aos="fade-up">
                    <h2>Principais Roteiros</h2>
                    <p class="lead">Conheça os roteiros mais procurados e planeje sua viagem com tranquilidade.</p>
                </div>
            </div>
            <div class="row">
                <?php 
                if($res_roteiros && $res_roteiros->num_rows > 0): 
                    while($dest = $res_roteiros->fetch_assoc()): 
                ?>
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up">
                    <a href="destinos/<?php echo htmlspecialchars($dest['slug']); ?>.php" class="card-link text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 card-hover" style="position: relative;">
                            <?php if($dest['is_featured']): ?>
                                <div class="badge-featured">⭐ Destaque</div>
                            <?php endif; ?>
                            <div class="card-img-overlay-wrapper">
                                <?php if(!empty($dest['imagem'])): ?>
                                    <img src="img/<?php echo htmlspecialchars($dest['imagem']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($dest['titulo']); ?>" loading="lazy">
                                <?php else: ?>
                                    <img src="img/img1.png" class="card-img-top" alt="<?php echo htmlspecialchars($dest['titulo']); ?>" loading="lazy">
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    📍 <?php echo htmlspecialchars($dest['titulo']); ?>
                                </h5>
                                <p class="card-text text-muted"><?php echo htmlspecialchars(substr($dest['descricao'], 0, 100)) . '...'; ?></p>
                                <div class="card-features">
                                    <span class="feature-tag">
                                        🏷️ <?php echo !empty($dest['tags']) ? htmlspecialchars($dest['tags']) : 'Turismo'; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <button class="btn btn-primary btn-sm w-100">
                                    Ver Roteiro →
                                </button>
                            </div>
                        </div>
                    </a>
                </div>
                <?php 
                    endwhile; 
                else: 
                ?>
                    <div class="col-12 text-center text-muted py-5">
                        <p>Ainda não há roteiros cadastrados no banco de dados.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- SEÇÃO DE DESTINOS -->
    <section id="destinos" class="content-section">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto" data-aos="fade-up">
                    <h2>Nossos Principais Destinos</h2>
                    <p class="lead">Descubra as joias turísticas da Serra Gaúcha</p>
                </div>
            </div>
            <div class="row">
                <?php 
                if($res_destinos && $res_destinos->num_rows > 0): 
                    while($dest = $res_destinos->fetch_assoc()): 
                ?>
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up">
                    <a href="destinos/<?php echo htmlspecialchars($dest['slug']); ?>.php" class="card-link text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 card-hover" style="position: relative;">
                            <?php if($dest['is_featured']): ?>
                                <div class="badge-featured">⭐ Destaque</div>
                            <?php endif; ?>
                            <div class="card-img-overlay-wrapper">
                                <?php if(!empty($dest['imagem'])): ?>
                                    <img src="img/<?php echo htmlspecialchars($dest['imagem']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($dest['titulo']); ?>" loading="lazy">
                                <?php else: ?>
                                    <img src="img/img1.png" class="card-img-top" alt="<?php echo htmlspecialchars($dest['titulo']); ?>" loading="lazy">
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    📍 <?php echo htmlspecialchars($dest['titulo']); ?>
                                </h5>
                                <p class="card-text text-muted"><?php echo htmlspecialchars(substr($dest['descricao'], 0, 100)) . '...'; ?></p>
                                <div class="card-features">
                                    <span class="feature-tag">
                                        🏷️ <?php echo !empty($dest['tags']) ? htmlspecialchars($dest['tags']) : 'Turismo'; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <button class="btn btn-primary btn-sm w-100">
                                    Explorar Destino →
                                </button>
                            </div>
                        </div>
                    </a>
                </div>
                <?php 
                    endwhile; 
                else: 
                ?>
                    <div class="col-12 text-center text-muted py-5">
                        <p>Nenhum destino cadastrado no banco de dados ainda.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- SEÇÃO DE SERVIÇOS -->
    <section id="servicos" class="content-section">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto" data-aos="fade-up">
                    <h2>Prestadores de Serviço</h2>
                    <p class="lead">Encontre agências, agentes e transportadores para transformar seu roteiro em realidade.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up">
                    <a href="servicos/agencias-de-turismo.php" class="card-link text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 card-hover">
                            <div class="card-body text-center">
                                <div class="mb-3" style="font-size: 3rem;">🧳</div>
                                <h5 class="card-title">Agências de Turismo</h5>
                                <p class="card-text text-muted">Planeje sua viagem com quem conhece a Serra Gaúcha e ofereça a melhor experiência.</p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <span class="btn btn-primary btn-sm w-100">Ver Agências →</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up">
                    <a href="servicos/agentes-de-turismo.php" class="card-link text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 card-hover">
                            <div class="card-body text-center">
                                <div class="mb-3" style="font-size: 3rem;">🧭</div>
                                <h5 class="card-title">Agentes de Turismo</h5>
                                <p class="card-text text-muted">Guias e agentes locais que conhecem cada rota e ponto turístico.</p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <span class="btn btn-primary btn-sm w-100">Ver Agentes →</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up">
                    <a href="servicos/transportadores-turisticos.php" class="card-link text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 card-hover">
                            <div class="card-body text-center">
                                <div class="mb-3" style="font-size: 3rem;">🚐</div>
                                <h5 class="card-title">Transportadores Turísticos</h5>
                                <p class="card-text text-muted">Viaje com conforto por roteiros cuidadosamente planejados.</p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <span class="btn btn-primary btn-sm w-100">Ver Transportadores →</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SEÇÃO DE DEPOIMENTOS -->
    <section id="depoimentos" class="content-section bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto" data-aos="fade-up">
                    <h2>O Que Dizem Nossos Visitantes</h2>
                    <p class="lead">Experiências reais de quem já conheceu o melhor da Serra Gaúcha</p>
                </div>
            </div>

            <?php if ($testimonial_feedback): ?>
                <div class="row mb-4">
                    <div class="col-lg-8 mx-auto">
                        <div class="alert alert-<?php echo htmlspecialchars($testimonial_feedback['type']); ?> text-center mb-0">
                            <?php echo htmlspecialchars($testimonial_feedback['text']); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <?php if ($res_testimonials && $res_testimonials->num_rows > 0): ?>
                    <?php while($test = $res_testimonials->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4" data-aos="fade-up">
                            <div class="testimonial-card h-100">
                                <div class="stars mb-3">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <p class="testimonial-text"><?php echo nl2br(htmlspecialchars($test['mensagem'])); ?></p>
                                <div class="testimonial-author">
                                    <div class="testimonial-avatar"><?php echo strtoupper(substr(htmlspecialchars($test['nome']), 0, 2)); ?></div>
                                    <strong><?php echo htmlspecialchars($test['nome']); ?></strong>
                                    <?php if(!empty($test['cidade']) || !empty($test['destino'])): ?>
                                        <p class="text-muted mb-0" style="font-size: 0.9rem;">
                                            <?php if(!empty($test['cidade'])): ?>📍 <?php echo htmlspecialchars($test['cidade']); ?><?php endif; ?>
                                            <?php if(!empty($test['destino'])): ?> • 🧭 <?php echo htmlspecialchars($test['destino']); ?><?php endif; ?>
                                        </p>
                                    <?php endif; ?>
                                    <p class="text-muted mb-0"><?php echo date('d/m/Y', strtotime($test['criado_em'])); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center text-muted py-5">
                        <p>Ainda não há depoimentos aprovados. Envie o seu abaixo!</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row mt-5">
                <div class="col-lg-8 mx-auto" data-aos="fade-up">
                    <div class="card p-4 shadow-sm border-0">
                        <h3 class="mb-3">Deixe seu depoimento</h3>
                        <p class="text-muted mb-4">Envie seu relato e, após aprovação pelo administrador, ele aparecerá nesta seção.</p>
                        <form method="post" action="submit_testimonial.php">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="testimonial-name" class="form-label">Nome</label>
                                    <input type="text" class="form-control" id="testimonial-name" name="nome" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="testimonial-email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="testimonial-email" name="email" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="testimonial-city" class="form-label">Cidade</label>
                                    <input type="text" class="form-control" id="testimonial-city" name="cidade" placeholder="Ex: Caxias do Sul">
                                </div>
                                <div class="col-md-6">
                                    <label for="testimonial-destination" class="form-label">Destino visitado</label>
                                    <input type="text" class="form-control" id="testimonial-destination" name="destino" placeholder="Ex: Santa Lúcia">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Avaliação</label>
                                    <div class="rating-stars" id="testimonial-rating">
                                        <input type="radio" id="nota-5" name="nota" value="5" required><label for="nota-5" title="5 estrelas">★</label>
                                        <input type="radio" id="nota-4" name="nota" value="4"><label for="nota-4" title="4 estrelas">★</label>
                                        <input type="radio" id="nota-3" name="nota" value="3"><label for="nota-3" title="3 estrelas">★</label>
                                        <input type="radio" id="nota-2" name="nota" value="2"><label for="nota-2" title="2 estrelas">★</label>
                                        <input type="radio" id="nota-1" name="nota" value="1"><label for="nota-1" title="1 estrela">★</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="testimonial-message" class="form-label">Mensagem</label>
                                    <textarea class="form-control" id="testimonial-message" name="mensagem" rows="4" required></textarea>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary">Enviar Depoimento</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

    </section>
</main>

    <script>
        // Validação básica do formulário de depoimentos
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.querySelector('form[action="submit_testimonial.php"]');
            if (!form) return;

            form.addEventListener('submit', function (event) {
                var name = form.querySelector('input[name="nome"]').value.trim();
                var email = form.querySelector('input[name="email"]').value.trim();
                var message = form.querySelector('textarea[name="mensagem"]').value.trim();
                var nota = form.querySelector('input[name="nota"]:checked');

                if (!name || !email || !message || !nota) {
                    event.preventDefault();
                    alert('Preencha todos os campos obrigatórios e selecione uma nota de 1 a 5 estrelas.');
                    return;
                }

                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    event.preventDefault();
                    alert('Digite um email válido.');
                    return;
                }
            });
        });
    </script>

<?php require_once 'includes/footer.php'; ?>
