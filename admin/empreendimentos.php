<?php
require_once 'config.php';
checkLogin();

// Buscar todos os empreendimentos e os nomes dos destinos com JOIN
$sql = "SELECT e.*, d.titulo as destino_titulo 
        FROM empreendimentos e 
        LEFT JOIN destinos d ON e.destino_id = d.id 
        ORDER BY d.titulo ASC, e.titulo ASC";
$res_empreendimentos = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Empreendimentos - Painel GxT</title>
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
        .table img { border-radius: 5px; object-fit: cover; }
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
            <a href="galeria.php"><i class="bi bi-images me-2"></i> Galeria de Fotos</a>
            <a href="banners.php"><i class="bi bi-layout-three-columns me-2"></i> Banners (Slides)</a>
            <a href="testimonials.php"><i class="bi bi-chat-left-text me-2"></i> Depoimentos</a>
            <a href="configuracoes.php"><i class="bi bi-gear-fill me-2"></i> Configurações</a>
            <a href="logout.php" class="text-danger mt-5"><i class="bi bi-box-arrow-right me-2"></i> Sair</a>
        </div>

        <!-- Conteúdo Principal -->
        <div class="col-md-9 col-lg-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                <h2 class="navbar-brand">Meus Empreendimentos</h2>
                <a href="empreendimento_form.php" class="btn text-white" style="background-color: #2E4636;">
                    <i class="bi bi-plus-circle"></i> Novo Empreendimento
                </a>
            </div>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'sucesso'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Ação realizada com sucesso!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card card-custom">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Imagem</th>
                                    <th>Empreendimento</th>
                                    <th>Destino Pai</th>
                                    <th>Tags/Tipo</th>
                                    <th class="text-end pe-4">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($res_empreendimentos && $res_empreendimentos->num_rows > 0): ?>
                                    <?php while($emp = $res_empreendimentos->fetch_assoc()): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <?php if(!empty($emp['imagem'])): ?>
                                                    <img src="../img/<?php echo htmlspecialchars($emp['imagem']); ?>" width="60" height="40" alt="img">
                                                <?php else: ?>
                                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 60px; height: 40px; border-radius: 5px; font-size: 10px;">Sem Img</div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong class="d-block text-dark"><?php echo htmlspecialchars($emp['titulo']); ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo htmlspecialchars($emp['destino_titulo'] ?? 'Desconhecido'); ?></span>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($emp['tags']); ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="empreendimento_form.php?id=<?php echo $emp['id']; ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="empreendimento_delete.php?id=<?php echo $emp['id']; ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Tem certeza que deseja apagar o empreendimento <?php echo htmlspecialchars(addslashes($emp['titulo'])); ?>?');" title="Excluir">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">Ainda não há nenhum empreendimento cadastrado. <br>Eles aparecerão dentro das páginas dos destinos!</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
