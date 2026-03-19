<?php
require_once 'config.php';
checkLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$acao = $id > 0 ? "Editar Empreendimento" : "Novo Empreendimento";

// Buscar lista de Destinos para o Select (Dropdown)
$res_destinos = $conn->query("SELECT id, titulo FROM destinos ORDER BY titulo ASC");
$lista_destinos = [];
while($row = $res_destinos->fetch_assoc()) {
    $lista_destinos[] = $row;
}

// Valores iniciais do empreendimento
$empreendimento = [
    'titulo' => '',
    'destino_id' => '',
    'descricao' => '',
    'contato' => '',
    'endereco' => '',
    'imagem' => '',
    'tags' => '',
    'alt_text' => ''
];

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM empreendimentos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res->num_rows > 0) {
        $empreendimento = $res->fetch_assoc();
    }
}

// Salvar no Banco
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $destino_id = (int)$_POST['destino_id'];
    $descricao = $_POST['descricao'] ?? '';
    $contato = $_POST['contato'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $alt_text = $_POST['alt_text'] ?? '';
    
    // Tratamento da imagem
    $imagem = $empreendimento['imagem'];
    if(isset($_FILES['img_upload']) && $_FILES['img_upload']['error'] === UPLOAD_ERR_OK) {
        $nomeTemp = $_FILES['img_upload']['tmp_name'];
        $extensao = strtolower(pathinfo($_FILES['img_upload']['name'], PATHINFO_EXTENSION));
        $formatos_permitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($extensao, $formatos_permitidos)) {
            $nomeArquivo = 'emp_' . time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '', $_FILES['img_upload']['name']);
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
            $stmt_up = $conn->prepare("UPDATE empreendimentos SET destino_id=?, titulo=?, descricao=?, contato=?, endereco=?, imagem=?, tags=?, alt_text=? WHERE id=?");
            $stmt_up->bind_param("isssssssi", $destino_id, $titulo, $descricao, $contato, $endereco, $imagem, $tags, $alt_text, $id);
            $stmt_up->execute();
        } else {
            // Insert
            $stmt_in = $conn->prepare("INSERT INTO empreendimentos (destino_id, titulo, descricao, contato, endereco, imagem, tags, alt_text) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_in->bind_param("isssssss", $destino_id, $titulo, $descricao, $contato, $endereco, $imagem, $tags, $alt_text);
            $stmt_in->execute();
        }
        header("Location: empreendimentos.php?msg=sucesso");
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
            <a href="destinos.php"><i class="bi bi-geo-alt-fill me-2"></i> Destinos / Roteiros</a>
            <a href="empreendimentos.php" class="active"><i class="bi bi-shop me-2"></i> Empreendimentos</a>
            <a href="prestadores.php"><i class="bi bi-people-fill me-2"></i> Prestadores de Serviço</a>
            <a href="configuracoes.php"><i class="bi bi-gear-fill me-2"></i> Configurações</a>
            <a href="logout.php" class="text-danger mt-5"><i class="bi bi-box-arrow-right me-2"></i> Sair</a>
        </div>

        <!-- Conteúdo Principal -->
        <div class="col-md-9 col-lg-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                <h2 class="navbar-brand"><?php echo $acao; ?></h2>
                <a href="empreendimentos.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>

            <?php if(!empty($erro)): ?>
                <div class="alert alert-danger mb-4"><?php echo $erro; ?></div>
            <?php endif; ?>

            <div class="card card-custom">
                <div class="card-body p-4">
                    <form action="empreendimento_form.php<?php echo $id > 0 ? '?id='.$id : ''; ?>" method="POST" enctype="multipart/form-data">
                        
                        <div class="row mb-3">
                            <div class="col-md-7">
                                <label class="form-label text-black-50 fw-bold">Nome do Empreendimento</label>
                                <input type="text" class="form-control form-control-lg" name="titulo" required value="<?php echo htmlspecialchars($empreendimento['titulo']); ?>" placeholder="Ex: Vinícola Don Giovanni">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-bold text-success">Destino (Pertence a onde?)</label>
                                <select name="destino_id" class="form-select form-select-lg" required>
                                    <option value="">-- Selecione o Destino Pai --</option>
                                    <?php foreach($lista_destinos as $d): ?>
                                        <option value="<?php echo $d['id']; ?>" <?php echo ($d['id'] == $empreendimento['destino_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($d['titulo']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descrição (Sobre o local)</label>
                            <textarea class="form-control" name="descricao" rows="3"><?php echo htmlspecialchars($empreendimento['descricao']); ?></textarea>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Contato (Whats / Tel)</label>
                                <input type="text" class="form-control" name="contato" value="<?php echo htmlspecialchars($empreendimento['contato']); ?>" placeholder="Ex: (54) 9999-9999">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Endereço Físico</label>
                                <input type="text" class="form-control" name="endereco" value="<?php echo htmlspecialchars($empreendimento['endereco']); ?>" placeholder="Ex: Estrada do Vinho, km 2">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Imagem (Anexar arquivo ou nome da foto)</label>
                                <?php if(!empty($empreendimento['imagem'])): ?>
                                    <div class="mb-2">
                                        <img src="../img/<?php echo htmlspecialchars($empreendimento['imagem']); ?>" style="max-height: 80px; border-radius: 5px;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control mb-2" name="img_upload" accept="image/*">
                                <input type="text" class="form-control" name="imagem_nome" value="<?php echo htmlspecialchars($empreendimento['imagem']); ?>" placeholder="Ex: foto_restaurante1.jpg">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Tags / Tipo de Serviço</label>
                                <input type="text" class="form-control" name="tags" value="<?php echo htmlspecialchars($empreendimento['tags']); ?>" placeholder="Ex: Restaurante, Vinícola, Hotel">
                            </div>

                            <div class="col-md-12 mt-3">
                                <label class="form-label">Descrição da Imagem (SEO - Alt Text)</label>
                                <input type="text" class="form-control" name="alt_text" value="<?php echo htmlspecialchars($empreendimento['alt_text'] ?? ''); ?>" placeholder="Ex: Fachada rústica da vinícola com parreirais ao fundo">
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-lg px-5" style="background-color: #2E4636; border: none;">Salvar Empreendimento</button>
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
