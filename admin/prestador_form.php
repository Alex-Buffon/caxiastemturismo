<?php
require_once 'config.php';
checkLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$p = [
    'nome' => '',
    'tipo' => 'agencia',
    'descricao' => '',
    'imagem' => '',
    'link_mapa' => '',
    'contato' => ''
];

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM prestadores WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $p = $res->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $tipo = $_POST['tipo'];
    $descricao = $_POST['descricao'];
    $link_mapa = $_POST['link_mapa'];
    $contato = $_POST['contato'];
    $imagem_final = $p['imagem'];

    // Upload de Imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $novo_nome = 'servico_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], '../img/' . $novo_nome)) {
            $imagem_final = $novo_nome;
        }
    }

    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE prestadores SET nome=?, tipo=?, descricao=?, imagem=?, link_mapa=?, contato=? WHERE id=?");
        $stmt->bind_param("ssssssi", $nome, $tipo, $descricao, $imagem_final, $link_mapa, $contato, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO prestadores (nome, tipo, descricao, imagem, link_mapa, contato) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nome, $tipo, $descricao, $imagem_final, $link_mapa, $contato);
    }

    if ($stmt->execute()) {
        header("Location: prestadores.php?msg=sucesso");
        exit;
    } else {
        $erro = "Erro ao salvar: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id > 0 ? 'Editar' : 'Novo'; ?> Prestador - Painel GxT</title>
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
        .card-form { background: white; border-radius: 10px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 25px;}
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
            <a href="prestadores.php" class="active"><i class="bi bi-people-fill me-2"></i> Prestadores de Serviço</a>
            <a href="configuracoes.php"><i class="bi bi-gear-fill me-2"></i> Configurações</a>
            <a href="logout.php" class="text-danger mt-5"><i class="bi bi-box-arrow-right me-2"></i> Sair</a>
        </div>

        <!-- Conteúdo Principal -->
        <div class="col-md-9 col-lg-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                <h2 class="navbar-brand"><?php echo $id > 0 ? 'Editar' : 'Adicionar'; ?> Prestador</h2>
                <a href="prestadores.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>

            <?php if(isset($erro)): ?>
                <div class="alert alert-danger"><?php echo $erro; ?></div>
            <?php endif; ?>

            <div class="card card-form">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome do Prestador/Empresa</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($p['nome']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo de Serviço</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="agencia" <?php echo $p['tipo'] == 'agencia' ? 'selected' : ''; ?>>Agência de Turismo</option>
                                    <option value="agente" <?php echo $p['tipo'] == 'agente' ? 'selected' : ''; ?>>Agente de Turismo</option>
                                    <option value="transportador" <?php echo $p['tipo'] == 'transportador' ? 'selected' : ''; ?>>Transportador Turístico</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição Curta (aparelha no card)</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="2"><?php echo htmlspecialchars($p['descricao']); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contato" class="form-label">Contato (Telefone/WhatsApp/Email)</label>
                                <input type="text" class="form-control" id="contato" name="contato" value="<?php echo htmlspecialchars($p['contato']); ?>" placeholder="(54) 99999-9999">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="link_mapa" class="form-label">Link do Google Maps (opcional)</label>
                                <input type="text" class="form-control" id="link_mapa" name="link_mapa" value="<?php echo htmlspecialchars($p['link_mapa']); ?>" placeholder="https://maps.google.com/...">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="imagem" class="form-label">Imagem do Prestador</label>
                        <input type="file" class="form-control" id="imagem" name="imagem">
                        <?php if(!empty($p['imagem'])): ?>
                            <div class="mt-2">
                                <small class="text-muted">Imagem atual:</small><br>
                                <img src="../img/<?php echo htmlspecialchars($p['imagem']); ?>" width="100" class="mt-1 rounded">
                            </div>
                        <?php endif; ?>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-lg text-white" style="background-color: #2E4636;">
                        <i class="bi bi-save me-2"></i> Salvar Prestador
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
