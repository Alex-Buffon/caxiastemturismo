<?php
require_once 'config.php';
checkLogin();

// Processar Ping do Sitemap para o Google
if (isset($_GET['ping_sitemap'])) {
    $sitemapUrl = urlencode('https://caxiastemturismo.com.br/sitemap.php');
    $pingUrl = "https://www.google.com/ping?sitemap=" . $sitemapUrl;
    
    $ch = curl_init($pingUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        header("Location: configuracoes.php?msg=ping_sucesso");
    } else {
        header("Location: configuracoes.php?msg=ping_erro");
    }
    exit;
}

// Salvar Configurações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['config'] as $chave => $valor) {
        $stmt = $conn->prepare("UPDATE opcoes_site SET valor = ? WHERE chave = ?");
        $stmt->bind_param("ss", $valor, $chave);
        $stmt->execute();
    }
    header("Location: configuracoes.php?msg=sucesso");
    exit;
}

// Buscar configurações existentes
$opcoes = [];
$result = $conn->query("SELECT chave, valor FROM opcoes_site");
while ($row = $result->fetch_assoc()) {
    $opcoes[$row['chave']] = $row['valor'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações do Site - Painel GxT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Lato', sans-serif; background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background-color: #2E4636; color: white; padding-top: 20px;}
        .sidebar a { color: rgba(255,255,255,0.8); text-decoration: none; display: block; padding: 10px 20px; font-weight: bold;}
        .sidebar a:hover, .sidebar a.active { background-color: rgba(255,255,255,0.1); color: white;}
        .main-content { padding: 30px; }
        .navbar-brand { font-family: 'Montserrat', sans-serif; font-weight: 700; }
        .card-custom { border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar px-0 d-none d-md-block">
            <h5 class="text-center mb-4 mt-2 navbar-brand">Caxias Tem<br>Turismo</h5>
            <a href="index.php"><i class="bi bi-speedometer2 me-2"></i> Início</a>
            <a href="destinos.php"><i class="bi bi-geo-alt-fill me-2"></i> Destinos / Roteiros</a>
            <a href="empreendimentos.php"><i class="bi bi-shop me-2"></i> Empreendimentos</a>
            <a href="prestadores.php"><i class="bi bi-people-fill me-2"></i> Prestadores de Serviço</a>
            <a href="galeria.php"><i class="bi bi-images me-2"></i> Galeria de Fotos</a>
            <a href="banners.php"><i class="bi bi-layout-three-columns me-2"></i> Banners (Slides)</a>
            <a href="testimonials.php"><i class="bi bi-chat-left-text me-2"></i> Depoimentos</a>
            <a href="configuracoes.php" class="active"><i class="bi bi-gear-fill me-2"></i> Configurações</a>
            <a href="logout.php" class="text-danger mt-5"><i class="bi bi-box-arrow-right me-2"></i> Sair</a>
        </div>

        <!-- Conteúdo Principal -->
        <div class="col-md-9 col-lg-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                <h2 class="navbar-brand text-primary">Configurações Gerais</h2>
            </div>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'sucesso'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Configurações salvas com sucesso!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif(isset($_GET['msg']) && $_GET['msg'] == 'ping_sucesso'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> Google notificado com sucesso! As atualizações no sitemap foram enviadas para indexação.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif(isset($_GET['msg']) && $_GET['msg'] == 'ping_erro'): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Houve um problema ao notificar o Google. Tente novamente mais tarde.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="configuracoes.php" method="POST">
                <div class="row g-4">
                    <!-- Identidade do Site -->
                    <div class="col-md-6">
                        <div class="card card-custom p-4 h-100">
                            <h5 class="mb-3 border-bottom pb-2"><i class="bi bi-info-circle me-2"></i> Identidade do Site</h5>
                            <div class="mb-3">
                                <label class="form-label">Título do Site</label>
                                <input type="text" name="config[site_titulo]" class="form-control" value="<?php echo htmlspecialchars($opcoes['site_titulo'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descrição (Metatag Description)</label>
                                <textarea name="config[site_descricao]" class="form-control" rows="3"><?php echo htmlspecialchars($opcoes['site_descricao'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Contato -->
                    <div class="col-md-6">
                        <div class="card card-custom p-4 h-100">
                            <h5 class="mb-3 border-bottom pb-2"><i class="bi bi-telephone me-2"></i> Informações de Contato</h5>
                            <div class="mb-3">
                                <label class="form-label">E-mail de Contato</label>
                                <input type="email" name="config[contato_email]" class="form-control" value="<?php echo htmlspecialchars($opcoes['contato_email'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Telefone Exibido</label>
                                <input type="text" name="config[contato_telefone]" class="form-control" value="<?php echo htmlspecialchars($opcoes['contato_telefone'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Número WhatsApp (Só números com DDD)</label>
                                <input type="text" name="config[contato_whatsapp]" class="form-control" value="<?php echo htmlspecialchars($opcoes['contato_whatsapp'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Endereço / Cidade</label>
                                <input type="text" name="config[contato_endereco]" class="form-control" value="<?php echo htmlspecialchars($opcoes['contato_endereco'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- SEO e Scripts -->
                    <div class="col-md-6">
                        <div class="card card-custom p-4 h-100">
                            <h5 class="mb-3 border-bottom pb-2"><i class="bi bi-search me-2"></i> SEO e Scripts</h5>
                            <div class="mb-3">
                                <label class="form-label">Palavras-chave (Keywords - separadas por vírgula)</label>
                                <textarea name="config[site_keywords]" class="form-control" rows="2"><?php echo htmlspecialchars($opcoes['site_keywords'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Google Analytics ID (Ex: G-XXXXXXXXXX)</label>
                                <input type="text" name="config[site_analytics_id]" class="form-control" value="<?php echo htmlspecialchars($opcoes['site_analytics_id'] ?? ''); ?>" placeholder="Opcional">
                            </div>
                        </div>
                    </div>

                    <!-- Conteúdo da Home -->
                    <div class="col-md-6">
                        <div class="card card-custom p-4 h-100">
                            <h5 class="mb-3 border-bottom pb-2"><i class="bi bi-window-sidebar me-2"></i> Seção Sobre (Página Inicial)</h5>
                            <div class="mb-3">
                                <label class="form-label">Título da Seção Sobre</label>
                                <input type="text" name="config[home_sobre_titulo]" class="form-control" value="<?php echo htmlspecialchars($opcoes['home_sobre_titulo'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Texto da Seção Sobre</label>
                                <textarea name="config[home_sobre_texto]" class="form-control" rows="4"><?php echo htmlspecialchars($opcoes['home_sobre_texto'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Estatísticas -->
                    <div class="col-md-12">
                        <div class="card card-custom p-4">
                            <h5 class="mb-3 border-bottom pb-2"><i class="bi bi-bar-chart-line me-2"></i> Estatísticas da Home</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Visitantes por Ano (Número)</label>
                                    <input type="number" name="config[home_stats_visitantes]" class="form-control" value="<?php echo htmlspecialchars($opcoes['home_stats_visitantes'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Roteiros Disponíveis (Número)</label>
                                    <input type="number" name="config[home_stats_roteiros]" class="form-control" value="<?php echo htmlspecialchars($opcoes['home_stats_roteiros'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Redes Sociais -->
                    <div class="col-12 mt-4">
                        <div class="card card-custom p-4">
                            <h5 class="mb-3 text-start border-bottom pb-2"><i class="bi bi-share me-2"></i> Redes Sociais</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3 text-start">
                                    <label class="form-label">Link Facebook</label>
                                    <input type="text" name="config[social_facebook]" class="form-control" value="<?php echo htmlspecialchars($opcoes['social_facebook'] ?? ''); ?>">
                                </div>
                                <div class="col-md-4 mb-3 text-start">
                                    <label class="form-label">Link Instagram</label>
                                    <input type="text" name="config[social_instagram]" class="form-control" value="<?php echo htmlspecialchars($opcoes['social_instagram'] ?? ''); ?>">
                                </div>
                                <div class="col-md-4 mb-3 text-start">
                                    <label class="form-label">Link Youtube</label>
                                    <input type="text" name="config[social_youtube]" class="form-control" value="<?php echo htmlspecialchars($opcoes['social_youtube'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ações Rápidas de SEO -->
                    <div class="col-12 mt-4">
                        <div class="card card-custom p-4 border-start border-4 border-success">
                            <h5 class="mb-3 text-start border-bottom pb-2"><i class="bi bi-rocket-takeoff text-success me-2"></i> Indexação no Google (SEO)</h5>
                            <div class="row">
                                <div class="col-md-9 text-start">
                                    <p class="mb-0 text-muted">Utilize o botão ao lado sempre que adicionar ou editar um <strong>Destino</strong>, <strong>Empreendimento</strong> ou <strong>Foto</strong>. Isso fará com que o Google decubra seu conteúdo instantaneamente e garanta a melhor classificação nas pesquisas.</p>
                                </div>
                                <div class="col-md-3 text-end align-self-center">
                                    <a href="configuracoes.php?ping_sitemap=1" class="btn btn-outline-success fw-bold w-100">
                                        <i class="bi bi-google me-1"></i> Notificar (Ping)
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-center my-4">
                        <button type="submit" class="btn btn-lg text-white px-5" style="background-color: #2E4636;">
                            <i class="bi bi-save me-2"></i> Salvar Todas as Configurações
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
