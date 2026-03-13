<?php
require_once 'config.php';
checkLogin(); // Somente para usuários logados

// Buscar estatísticas
$stmt_d = $conn->query("SELECT COUNT(*) as total FROM destinos");
$total_destinos = $stmt_d->fetch_assoc()['total'];

$stmt_e = $conn->query("SELECT COUNT(*) as total FROM empreendimentos");
$total_emps = $stmt_e->fetch_assoc()['total'];

// Garantir tabela de depoimentos exista (para evitar erro se ainda não foi criada)
$conn->query("CREATE TABLE IF NOT EXISTS depoimentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  mensagem TEXT NOT NULL,
  status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  aprovado_em TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

$stmt_p = $conn->query("SELECT COUNT(*) as total FROM prestadores");
$total_prestadores = $stmt_p->fetch_assoc()['total'];

$stmt_t = $conn->query("SELECT COUNT(*) as total FROM depoimentos WHERE status = 'pending'");
$total_testimonials = $stmt_t->fetch_assoc()['total'];

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Caxias Tem Turismo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Lato', sans-serif; background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background-color: #2E4636; color: white; padding-top: 20px;}
        .sidebar a { color: rgba(255,255,255,0.8); text-decoration: none; display: block; padding: 10px 20px; font-weight: bold;}
        .sidebar a:hover, .sidebar a.active { background-color: rgba(255,255,255,0.1); color: white;}
        .main-content { padding: 30px; }
        .card-stat { border-left: 5px solid #2E4636; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .navbar-brand { font-family: 'Montserrat', sans-serif; font-weight: 700; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar px-0 d-none d-md-block">
            <h5 class="text-center mb-4 mt-2 navbar-brand">Caxias Tem<br>Turismo</h5>
            <a href="index.php" class="active"><i class="bi bi-speedometer2 me-2"></i> Início</a>
            <a href="destinos.php"><i class="bi bi-geo-alt-fill me-2"></i> Destinos / Roteiros</a>
            <a href="empreendimentos.php"><i class="bi bi-shop me-2"></i> Empreendimentos</a>
            <a href="prestadores.php"><i class="bi bi-people-fill me-2"></i> Prestadores de Serviço</a>
            <a href="galeria.php"><i class="bi bi-images me-2"></i> Galeria de Fotos</a>
            <a href="banners.php"><i class="bi bi-layout-three-columns me-2"></i> Banners (Slides)</a>
            <a href="testimonials.php"><i class="bi bi-chat-left-text me-2"></i> Depoimentos</a>
            <a href="configuracoes.php"><i class="bi bi-gear-fill me-2"></i> Configurações</a>
            <a href="logout.php" class="text-danger mt-5"><i class="bi bi-box-arrow-right me-2"></i> Sair</a>
        </div>

        <!-- Conteúdo Principal -->
        <div class="col-md-9 col-lg-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                <h2 class="navbar-brand">Dashboard</h2>
                <div class="user-info">
                    <span>Olá, <?php echo htmlspecialchars($_SESSION['admin_nome']); ?></span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card card-stat mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-muted">Destinos Cadastrados</h5>
                            <h2 class="mb-0 text-dark"><?php echo $total_destinos; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stat mb-4" style="border-left-color: #2E4636;">
                        <div class="card-body">
                            <h5 class="card-title text-muted">Empreendimentos</h5>
                            <h2 class="mb-0 text-dark"><?php echo $total_emps; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stat mb-4" style="border-left-color: #D0A94F;">
                        <div class="card-body">
                            <h5 class="card-title text-muted">Depoimentos Pendentes</h5>
                            <h2 class="mb-0 text-dark"><?php echo $total_testimonials; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h4>Bem-vindo ao Novo Painel</h4>
                            <p>Aqui você poderá controlar todas as informações do site, trocar textos e adicionar novas fotos com facilidade. Use o menu lateral para navegar e começar a gerenciar o conteúdo!</p>
                            
                            <a href="destinos.php" class="btn btn-primary mt-3 me-2" style="background-color: #2E4636; border: none;">
                                <i class="bi bi-plus-circle me-2"></i> Ver Destinos
                            </a>
                            <a href="empreendimentos.php" class="btn btn-primary mt-3" style="background-color: #2E4636; border: none;">
                                <i class="bi bi-shop me-2"></i> Gerenciar Empreendimentos
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
