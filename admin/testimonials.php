<?php
require_once 'config.php';
checkLogin();

// Garantir que tabela existe
$conn->query("CREATE TABLE IF NOT EXISTS depoimentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  cidade VARCHAR(255) DEFAULT NULL,
  destino VARCHAR(255) DEFAULT NULL,
  mensagem TEXT NOT NULL,
  status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  aprovado_em TIMESTAMP NULL DEFAULT NULL,
  ip VARCHAR(45) DEFAULT NULL,
  user_agent TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

// Adicionar colunas caso estejam faltando (compatibilidade com versões antigas)
$conn->query("ALTER TABLE depoimentos ADD COLUMN IF NOT EXISTS cidade VARCHAR(255) DEFAULT NULL;");
$conn->query("ALTER TABLE depoimentos ADD COLUMN IF NOT EXISTS destino VARCHAR(255) DEFAULT NULL;");
$conn->query("ALTER TABLE depoimentos ADD COLUMN IF NOT EXISTS ip VARCHAR(45) DEFAULT NULL;");
$conn->query("ALTER TABLE depoimentos ADD COLUMN IF NOT EXISTS user_agent TEXT DEFAULT NULL;");

if (isset($_GET['action'], $_GET['id'])) {
    $id = (int) $_GET['id'];
    if ($id > 0) {
        if ($_GET['action'] === 'approve') {
            $stmt = $conn->prepare("UPDATE depoimentos SET status = 'approved', aprovado_em = NOW() WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
            header('Location: testimonials.php?msg=approved');
            exit;
        }
        if ($_GET['action'] === 'reject') {
            $stmt = $conn->prepare("UPDATE depoimentos SET status = 'rejected' WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
            header('Location: testimonials.php?msg=rejected');
            exit;
        }
        if ($_GET['action'] === 'delete') {
            $stmt = $conn->prepare("DELETE FROM depoimentos WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
            header('Location: testimonials.php?msg=deleted');
            exit;
        }
    }
}

// Cabeça de filtro de pesquisa
$searchQuery = trim($_GET['q'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');
$cidadeFilter = trim($_GET['cidade'] ?? '');
$destinoFilter = trim($_GET['destino'] ?? '');


$where = [];
$params = [];
$types = '';

if ($searchQuery !== '') {
    $where[] = "(nome LIKE ? OR email LIKE ? OR mensagem LIKE ? OR destino LIKE ? OR cidade LIKE ?)";
    $like = "%{$searchQuery}%";
    for ($i = 0; $i < 5; $i++) {
        $params[] = $like;
    }
    $types .= str_repeat('s', 5);
}

if ($statusFilter !== '') {
    $where[] = "status = ?";
    $params[] = $statusFilter;
    $types .= 's';
}

if ($cidadeFilter !== '') {
    $where[] = "cidade LIKE ?";
    $params[] = "%{$cidadeFilter}%";
    $types .= 's';
}

if ($destinoFilter !== '') {
    $where[] = "destino LIKE ?";
    $params[] = "%{$destinoFilter}%";
    $types .= 's';
}

$sql = "SELECT * FROM depoimentos";
if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY criado_em DESC";

$stmt = $conn->prepare($sql);
if ($types !== '') {
    $bind_names = [];
    $bind_names[] = $types;
    foreach ($params as $key => $value) {
        $bind_names[] = &$params[$key];
    }
    call_user_func_array([$stmt, 'bind_param'], $bind_names);
}
$stmt->execute();
$res = $stmt->get_result();

$pending_count = $conn->query("SELECT COUNT(*) AS c FROM depoimentos")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Depoimentos - Painel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Lato', sans-serif; background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background-color: #2E4636; color: white; padding-top: 20px; }
        .sidebar a { color: rgba(255,255,255,0.8); text-decoration: none; display: block; padding: 10px 20px; font-weight: bold; }
        .sidebar a:hover, .sidebar a.active { background-color: rgba(255,255,255,0.1); color: white; }
        .main-content { padding: 30px; }
        .navbar-brand { font-family: 'Montserrat', sans-serif; font-weight: 700; }
        .table-responsive { max-height: 70vh; overflow: auto; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 sidebar px-0 d-none d-md-block">
            <h5 class="text-center mb-4 mt-2 navbar-brand">Caxias Tem<br>Turismo</h5>
            <a href="index.php"><i class="bi bi-speedometer2 me-2"></i> Início</a>
            <a href="destinos.php"><i class="bi bi-geo-alt-fill me-2"></i> Destinos / Roteiros</a>
            <a href="empreendimentos.php"><i class="bi bi-shop me-2"></i> Empreendimentos</a>
            <a href="prestadores.php"><i class="bi bi-people-fill me-2"></i> Prestadores de Serviço</a>
            <a href="galeria.php"><i class="bi bi-images me-2"></i> Galeria de Fotos</a>
            <a href="banners.php"><i class="bi bi-layout-three-columns me-2"></i> Banners (Slides)</a>
            <a href="configuracoes.php"><i class="bi bi-gear-fill me-2"></i> Configurações</a>
            <a href="testimonials.php" class="active"><i class="bi bi-chat-left-text me-2"></i> Depoimentos <span class="badge bg-warning text-dark"><?php echo $pending_count; ?></span></a>
            <a href="logout.php" class="text-danger mt-5"><i class="bi bi-box-arrow-right me-2"></i> Sair</a>
        </div>

        <div class="col-md-9 col-lg-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                <h2 class="navbar-brand">Depoimentos</h2>
                <div>
                    <?php if(isset($_GET['msg'])): ?>
                        <?php if($_GET['msg'] === 'approved'): ?>
                            <div class="alert alert-success mb-0">Depoimento aprovado com sucesso.</div>
                        <?php elseif($_GET['msg'] === 'rejected'): ?>
                            <div class="alert alert-warning mb-0">Depoimento marcado como rejeitado.</div>
                        <?php elseif($_GET['msg'] === 'deleted'): ?>
                            <div class="alert alert-danger mb-0">Depoimento excluído.</div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <form method="get" class="row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="q" class="form-control" placeholder="Pesquisar (nome, email, mensagem...)" value="<?php echo htmlspecialchars($searchQuery); ?>">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Todos os status</option>
                        <option value="pending" <?php echo $statusFilter === 'pending' ? 'selected' : ''; ?>>Pendente</option>
                        <option value="approved" <?php echo $statusFilter === 'approved' ? 'selected' : ''; ?>>Aprovado</option>
                        <option value="rejected" <?php echo $statusFilter === 'rejected' ? 'selected' : ''; ?>>Rejeitado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="cidade" class="form-control" placeholder="Cidade" value="<?php echo htmlspecialchars($cidadeFilter); ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" name="destino" class="form-control" placeholder="Destino" value="<?php echo htmlspecialchars($destinoFilter); ?>">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <a href="testimonials.php" class="btn btn-outline-secondary">Limpar</a>
                    <a href="testimonials_export.php" class="btn btn-outline-info">Exportar CSV</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Cidade</th>
                            <th>Destino</th>
                            <th>Nota</th>
                            <th>Mensagem</th>
                            <th>Status</th>
                            <th>Enviado</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($res && $res->num_rows > 0): ?>
                            <?php while($row = $res->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['cidade']); ?></td>
                                    <td><?php echo htmlspecialchars($row['destino']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nota']); ?>/5</td>
                                    <td style="max-width: 360px; white-space: pre-wrap; word-break: break-word; "><?php echo nl2br(htmlspecialchars($row['mensagem'])); ?></td>
                                    <td>
                                        <?php if($row['status'] === 'approved'): ?>
                                            <span class="badge bg-success">Aprovado</span>
                                        <?php elseif($row['status'] === 'rejected'): ?>
                                            <span class="badge bg-danger">Rejeitado</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pendente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['criado_em'])); ?></td>
                                    <td class="text-end">
                                        <?php if($row['status'] !== 'approved'): ?>
                                            <a href="testimonials.php?action=approve&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success me-1" title="Aprovar">Aprovar</a>
                                        <?php endif; ?>
                                        <?php if($row['status'] !== 'rejected'): ?>
                                            <a href="testimonials.php?action=reject&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning me-1" title="Rejeitar">Rejeitar</a>
                                        <?php endif; ?>
                                        <a href="testimonials.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir este depoimento?');" title="Excluir">Excluir</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">Ainda não há depoimentos enviados.</td>
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
