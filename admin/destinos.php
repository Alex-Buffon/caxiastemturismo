<?php
require_once 'config.php';
checkLogin();

// Buscar todos os destinos
$resultado = $conn->query("SELECT * FROM destinos ORDER BY is_featured DESC, ordem ASC, id DESC");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Destinos - Painel</title>
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
        .table-custom { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .badge-destaque { background-color: #2E4636; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar px-0 d-none d-md-block">
            <h5 class="text-center mb-4 mt-2 navbar-brand">Caxias Tem<br>Turismo</h5>
            <a href="index.php"><i class="bi bi-speedometer2 me-2"></i> Início</a>
            <a href="destinos.php" class="active"><i class="bi bi-geo-alt-fill me-2"></i> Destinos / Roteiros</a>
            <a href="empreendimentos.php"><i class="bi bi-shop me-2"></i> Empreendimentos</a>
            <a href="prestadores.php"><i class="bi bi-people-fill me-2"></i> Prestadores de Serviço</a>
            <a href="galeria.php"><i class="bi bi-images me-2"></i> Galeria de Fotos</a>
            <a href="banners.php"><i class="bi bi-layout-three-columns me-2"></i> Banners (Slides)</a>
            <a href="testimonials.php"><i class="bi bi-chat-left-text me-2"></i> Depoimentos</a>
            <a href="comunidade_moderar.php"><i class="bi bi-globe me-2"></i> Comunidade</a>
            <a href="configuracoes.php"><i class="bi bi-gear-fill me-2"></i> Configurações</a>
            <a href="logout.php" class="text-danger mt-5"><i class="bi bi-box-arrow-right me-2"></i> Sair</a>
        </div>

        <!-- Conteúdo Principal -->
        <div class="col-md-9 col-lg-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                <h2 class="navbar-brand">Gerenciar Destinos</h2>
                <a href="destino_form.php" class="btn btn-primary" style="background-color: #2E4636; border: none;">
                    <i class="bi bi-plus-lg"></i> Novo Destino
                </a>
            </div>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'sucesso'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Ação realizada com sucesso!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive table-custom">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Imagem</th>
                            <th scope="col">Título</th>
                            <th scope="col">Destaque?</th>
                            <th scope="col">Tags</th>
                            <th scope="col" class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado->num_rows > 0): ?>
                            <?php while($destino = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td style="width: 100px;">
                                        <?php if(!empty($destino['imagem'])): ?>
                                            <img src="../img/<?php echo htmlspecialchars($destino['imagem']); ?>" alt="Img" class="img-thumbnail" style="height: 60px; width: 80px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-secondary text-white text-center rounded d-flex align-items-center justify-content-center" style="height: 60px; width: 80px;"><i class="bi bi-image"></i></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($destino['titulo']); ?></strong>
                                    </td>
                                    <td>
                                        <?php if($destino['is_featured']): ?>
                                            <span class="badge badge-destaque">Sim <i class="bi bi-star-fill"></i></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Não</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($destino['tags']); ?></td>
                                    <td class="text-end">
                                        <a href="destino_form.php?id=<?php echo $destino['id']; ?>" class="btn btn-sm btn-outline-secondary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="post" action="destino_delete.php" style="display:inline-block; margin:0;">
                                            <input type="hidden" name="id" value="<?php echo $destino['id']; ?>">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja apagar este destino?');">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Ainda não há destinos cadastrados. Clique em "Novo Destino" no botão acima!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
