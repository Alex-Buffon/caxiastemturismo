<?php
require_once 'config.php';
checkLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$acao = $id > 0 ? "Editar Destino" : "Novo Destino";

// Valores iniciais
$destino = [
    'titulo' => '',
    'slug' => '',
    'descricao' => '',
    'imagem' => '',
    'tags' => '',
    'ordem' => 0,
    'is_featured' => 0,
    'meta_keywords' => '',
    'meta_description' => ''
];

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM destinos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res->num_rows > 0) {
        $destino = $res->fetch_assoc();
    }
}

// Criar slug a partir do titulo
function createSlug($string) {
    $slug = mb_strtolower($string, 'UTF-8');
    $slug = preg_replace('/[áàãâä]/u', 'a', $slug);
    $slug = preg_replace('/[éèêë]/u', 'e', $slug);
    $slug = preg_replace('/[íìîï]/u', 'i', $slug);
    $slug = preg_replace('/[óòõôö]/u', 'o', $slug);
    $slug = preg_replace('/[úùûü]/u', 'u', $slug);
    $slug = preg_replace('/[ç]/u', 'c', $slug);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    return trim($slug, '-');
}

// Salvar
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $slug = !empty($_POST['slug']) ? $_POST['slug'] : createSlug($titulo);
    $descricao = $_POST['descricao'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $ordem = (int)($_POST['ordem'] ?? 0);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $meta_keywords = $_POST['meta_keywords'] ?? '';
    $meta_description = $_POST['meta_description'] ?? '';
    
    // Tratamento basico da imagem
    $imagem = $destino['imagem']; // original
    if(isset($_FILES['img_upload']) && $_FILES['img_upload']['error'] === UPLOAD_ERR_OK) {
        $nomeTemp = $_FILES['img_upload']['tmp_name'];
        $extensao = strtolower(pathinfo($_FILES['img_upload']['name'], PATHINFO_EXTENSION));
        $formatos_permitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($extensao, $formatos_permitidos)) {
            $nomeArquivo = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '', $_FILES['img_upload']['name']);
            $destinoPath = '../img/' . $nomeArquivo;
            if(move_uploaded_file($nomeTemp, $destinoPath)) {
                $imagem = $nomeArquivo;
            }
        } else {
            $erro = "Formato de imagem não permitido. Use JPG, PNG, GIF ou WEBP.";
        }
    } else if(!empty($_POST['imagem_nome'])) {
        $imagem = $_POST['imagem_nome'];
    }
    
    if (empty($erro)) {
        if($id > 0) {
            // Update
            $stmt_up = $conn->prepare("UPDATE destinos SET titulo=?, slug=?, descricao=?, imagem=?, tags=?, ordem=?, is_featured=?, meta_keywords=?, meta_description=? WHERE id=?");
            $stmt_up->bind_param("sssssiissi", $titulo, $slug, $descricao, $imagem, $tags, $ordem, $is_featured, $meta_keywords, $meta_description, $id);
            $stmt_up->execute();
        } else {
            // Insert
            $stmt_in = $conn->prepare("INSERT INTO destinos (titulo, slug, descricao, imagem, tags, ordem, is_featured, meta_keywords, meta_description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_in->bind_param("sssssiiss", $titulo, $slug, $descricao, $imagem, $tags, $ordem, $is_featured, $meta_keywords, $meta_description);
            $stmt_in->execute();
        }
        header("Location: destinos.php?msg=sucesso");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $acao; ?> - Painel</title>
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
            <a href="configuracoes.php"><i class="bi bi-gear-fill me-2"></i> Configurações</a>
            <a href="logout.php" class="text-danger mt-5"><i class="bi bi-box-arrow-right me-2"></i> Sair</a>
        </div>

        <!-- Conteúdo Principal -->
        <div class="col-md-9 col-lg-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                <h2 class="navbar-brand"><?php echo $acao; ?></h2>
                <a href="destinos.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>

            <?php if(!empty($erro)): ?>
                <div class="alert alert-danger mb-4"><?php echo $erro; ?></div>
            <?php endif; ?>

            <div class="card card-custom">
                <div class="card-body p-4">
                    <form action="destino_form.php<?php echo $id > 0 ? '?id='.$id : ''; ?>" method="POST" enctype="multipart/form-data">
                        
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label class="form-label">Título do Destino</label>
                                <input type="text" class="form-control" name="titulo" required value="<?php echo htmlspecialchars($destino['titulo']); ?>" placeholder="Ex: Galópolis">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Slug (URL - opcional)</label>
                                <input type="text" class="form-control" name="slug" value="<?php echo htmlspecialchars($destino['slug']); ?>" placeholder="Ex: galopolis">
                                <small class="text-muted">Deixe em branco para gerar automático.</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descrição (Texto que vai no Card)</label>
                            <textarea class="form-control" name="descricao" rows="4" required><?php echo htmlspecialchars($destino['descricao']); ?></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Imagem (Nome do arquivo ou Enviar Nova)</label>
                                <?php if(!empty($destino['imagem'])): ?>
                                    <div class="mb-2">
                                        <img src="../img/<?php echo htmlspecialchars($destino['imagem']); ?>" style="max-height: 100px; border-radius: 5px;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control mb-2" name="img_upload" accept="image/*">
                                <input type="text" class="form-control" name="imagem_nome" value="<?php echo htmlspecialchars($destino['imagem']); ?>" placeholder="Ou digite o nome ex: img.galopolis.png">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Tage/Ícone (Ex: História, Natureza)</label>
                                <input type="text" class="form-control" name="tags" value="<?php echo htmlspecialchars($destino['tags']); ?>">
                                
                                <div class="mt-4 form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1" <?php echo $destino['is_featured'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label ms-2" for="is_featured">É destaque? (Aparece no Carousel/Topo)</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2 mt-4"><i class="bi bi-search me-2"></i> SEO Avançado (Opcional)</h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Palavras-chave SEO (Separadas por vírgula)</label>
                                <input type="text" class="form-control" name="meta_keywords" value="<?php echo htmlspecialchars($destino['meta_keywords'] ?? ''); ?>" placeholder="Ex: turismo rural, caxias, santa lucia">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Descrição SEO (Para o Google)</label>
                                <textarea class="form-control" name="meta_description" rows="2" placeholder="Uma breve descrição que atraia cliques no Google"><?php echo htmlspecialchars($destino['meta_description'] ?? ''); ?></textarea>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-lg px-5" style="background-color: #2E4636; border: none;">Salvar Destino</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
