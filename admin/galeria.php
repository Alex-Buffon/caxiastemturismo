<?php
require_once 'config.php';
checkLogin();

// Processar Upload de Imagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagem'])) {
    $legenda = $_POST['legenda'] ?? '';
    $ordem = (int)($_POST['ordem'] ?? 0);
    $alt_text = $_POST['alt_text'] ?? '';
    
    $arquivo = $_FILES['imagem'];
    $nome_original = basename($arquivo['name']);
    $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
    $novo_nome = 'galeria_' . time() . '.' . $extensao;
    $destino_img = '../img/' . $novo_nome;

    $formatos_permitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array($extensao, $formatos_permitidos)) {
        if (move_uploaded_file($arquivo['tmp_name'], $destino_img)) {
            $stmt = $conn->prepare("INSERT INTO galeria (imagem, legenda, ordem, alt_text) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssis", $novo_nome, $legenda, $ordem, $alt_text);
            $stmt->execute();
            header("Location: galeria.php?msg=sucesso");
            exit;
        } else {
            $erro = "Erro ao mover o arquivo para a pasta img.";
        }
    } else {
        $erro = "Formato de arquivo não permitido. Use JPG, PNG, GIF ou WEBP.";
    }
}

// Processar Exclusão
if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];
    
    // Buscar nome da imagem para deletar o arquivo
    $stmt = $conn->prepare("SELECT imagem FROM galeria WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $imagem_path = '../img/' . $row['imagem'];
        if (file_exists($imagem_path)) {
            unlink($imagem_path);
        }
        
        $stmt_del = $conn->prepare("DELETE FROM galeria WHERE id = ?");
        $stmt_del->bind_param("i", $id);
        $stmt_del->execute();
        header("Location: galeria.php?msg=excluido");
        exit;
    }
}

// Buscar todas as imagens
$sql = "SELECT * FROM galeria ORDER BY ordem ASC, id DESC";
$res_galeria = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Galeria - Painel GxT</title>
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
        .card-custom { background: white; border-radius: 10px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .gallery-preview { width: 100%; height: 150px; object-fit: cover; border-radius: 8px 8px 0 0; }
        .gallery-card { transition: transform 0.2s; border: none; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-radius: 8px; }
        .gallery-card:hover { transform: translateY(-5px); }
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
            <a href="galeria.php" class="active"><i class="bi bi-images me-2"></i> Galeria de Fotos</a>
            <a href="banners.php"><i class="bi bi-layout-three-columns me-2"></i> Banners (Slides)</a>
            <a href="testimonials.php"><i class="bi bi-chat-left-text me-2"></i> Depoimentos</a>
            <a href="logout.php" class="text-danger mt-5"><i class="bi bi-box-arrow-right me-2"></i> Sair</a>
        </div>

        <!-- Conteúdo Principal -->
        <div class="col-md-9 col-lg-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                <h2 class="navbar-brand">Gerenciar Galeria</h2>
                <button type="button" class="btn text-white" style="background-color: #2E4636;" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="bi bi-cloud-upload"></i> Nova Imagem
                </button>
            </div>

            <?php if(isset($_GET['msg'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                        if($_GET['msg'] == 'sucesso') echo "Imagem adicionada com sucesso!";
                        if($_GET['msg'] == 'excluido') echo "Imagem removida com sucesso!";
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if(isset($erro)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $erro; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <?php if($res_galeria && $res_galeria->num_rows > 0): ?>
                    <?php while($img = $res_galeria->fetch_assoc()): ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="card gallery-card h-100">
                                <img src="../img/<?php echo htmlspecialchars($img['imagem']); ?>" class="gallery-preview" alt="preview">
                                <div class="card-body p-2">
                                    <p class="card-text small mb-1 text-truncate" title="<?php echo htmlspecialchars($img['legenda']); ?>">
                                        <?php echo !empty($img['legenda']) ? htmlspecialchars($img['legenda']) : '<span class="text-muted italic">Sem legenda</span>'; ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-light text-dark border">Ordem: <?php echo $img['ordem']; ?></span>
                                        <a href="galeria.php?excluir=<?php echo $img['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja excluir esta imagem?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="bi bi-images" style="font-size: 3rem;"></i>
                        <p class="mt-3">Ainda não há imagens na galeria. Adicione a primeira!</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<!-- Modal Upload -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Adicionar Nova Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="galeria.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="imagem" class="form-label">Arquivo de Imagem</label>
                        <input type="file" class="form-control" id="imagem" name="imagem" required accept="image/*">
                        <small class="text-muted">Formatos: JPG, PNG, WEBP, GIF</small>
                    </div>
                    <div class="mb-3">
                        <label for="legenda" class="form-label">Legenda (Opcional)</label>
                        <input type="text" class="form-control" id="legenda" name="legenda" placeholder="Ex: Vista panorâmica de Galópolis">
                    </div>
                    <div class="mb-3">
                        <label for="alt_text" class="form-label">Descrição da Imagem (SEO - Alt Text)</label>
                        <input type="text" class="form-control" id="alt_text" name="alt_text" placeholder="Ex: Igreja centenária com arquitetura italiana em Caxias">
                    </div>
                    <div class="mb-3">
                        <label for="ordem" class="form-label">Ordem de Exibição</label>
                        <input type="number" class="form-control" id="ordem" name="ordem" value="0">
                        <small class="text-muted">Números menores aparecem primeiro.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn text-white" style="background-color: #2E4636;">Salvar Imagem</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
