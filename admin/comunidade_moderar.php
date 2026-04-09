<?php
// admin/comunidade_moderar.php
require_once 'config.php';
checkLogin();

// Lida com aprovação/rejeição
if(isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    if($action == 'approve') {
        $conn->query("UPDATE blog_comunidade SET status = 'approved' WHERE id = $id");
    } elseif($action == 'reject') {
        // Obter imagens para apagar
        $res = $conn->query("SELECT imagens FROM blog_comunidade WHERE id = $id");
        if($row = $res->fetch_assoc()){
            $imgs = json_decode($row['imagens'], true);
            if(!empty($imgs)){
                foreach($imgs as $img) {
                    @unlink('../img/comunidade/' . $img);
                }
            }
        }
        $conn->query("DELETE FROM blog_comunidade WHERE id = $id");
    }
    header("Location: comunidade_moderar.php");
    exit;
}

// Buscar pendentes
$sql = "SELECT * FROM blog_comunidade WHERE status = 'pending' ORDER BY data_envio ASC";
$pendentes = $conn->query($sql);

// Buscar aprovados limitados
$sql_app = "SELECT * FROM blog_comunidade WHERE status = 'approved' ORDER BY data_envio DESC LIMIT 20";
$aprovados = $conn->query($sql_app);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Moderar Comunidade - Dashboard</title>
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
        .post-img { width: 100%; height: 150px; object-fit: cover; border-radius: 8px;}
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
            <a href="comunidade_moderar.php" class="active"><i class="bi bi-globe me-2"></i> Comunidade</a>
            <a href="configuracoes.php"><i class="bi bi-gear-fill me-2"></i> Configurações</a>
            <a href="logout.php" class="text-danger mt-5"><i class="bi bi-box-arrow-right me-2"></i> Sair</a>
        </div>

        <div class="col-md-9 col-lg-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                <h2 class="navbar-brand">Moderar Comunidade</h2>
            </div>

            <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold text-danger" id="pendentes-tab" data-bs-toggle="tab" data-bs-target="#pendentes" type="button" role="tab">Pendente (Aprovação) <span class="badge bg-danger ms-1"><?php echo $pendentes->num_rows; ?></span></button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-success" id="aprovados-tab" data-bs-toggle="tab" data-bs-target="#aprovados" type="button" role="tab">Já Aprovados na Comunidade</button>
              </li>
            </ul>
            
            <div class="tab-content" id="myTabContent">
              <!-- Aba Pendentes -->
              <div class="tab-pane fade show active" id="pendentes" role="tabpanel">
                  
                  <?php if($pendentes->num_rows > 0): ?>
                  <div class="row g-4">
                      <?php while($p = $pendentes->fetch_assoc()): ?>
                      <div class="col-md-6 col-lg-4">
                          <div class="card h-100 shadow-sm border-warning">
                              <div class="card-header bg-warning text-dark fw-bold">
                                  Revisão Pendente
                              </div>
                              <div class="card-body">
                                  <h5 class="card-title"><?php echo htmlspecialchars($p['titulo']); ?></h5>
                                  <h6 class="card-subtitle mb-2 text-muted">Por <?php echo htmlspecialchars($p['autor_nome']); ?> (<?php echo htmlspecialchars($p['autor_email']); ?>)</h6>
                                  <p class="card-text small bg-light p-2 rounded"><?php echo nl2br(htmlspecialchars($p['conteudo'])); ?></p>
                                  
                                  <?php if(!empty($p['video_link'])): ?>
                                    <p class="small text-primary"><i class="bi bi-youtube"></i> Link: <?php echo htmlspecialchars($p['video_link']); ?></p>
                                  <?php endif; ?>
                                  
                                  <?php 
                                  $imgs = json_decode($p['imagens'], true);
                                  if(!empty($imgs)): 
                                  ?>
                                  <div class="d-flex gap-2 overflow-auto py-2">
                                      <?php foreach($imgs as $img): ?>
                                      <a href="../img/comunidade/<?php echo $img; ?>" target="_blank">
                                        <img src="../img/comunidade/<?php echo $img; ?>" class="post-img" style="width: 80px; height: 80px;">
                                      </a>
                                      <?php endforeach; ?>
                                  </div>
                                  <?php endif; ?>

                              </div>
                              <div class="card-footer bg-white d-flex justify-content-between border-0 pb-3">
                                  <a href="?action=reject&id=<?php echo $p['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Tem certeza? Isso apagará a postagem permanentemente.');">Rejeitar/Apagar</a>
                                  <a href="?action=approve&id=<?php echo $p['id']; ?>" class="btn btn-success btn-sm fw-bold">👍 Mover para o Site Público</a>
                              </div>
                          </div>
                      </div>
                      <?php endwhile; ?>
                  </div>
                  <?php else: ?>
                  <div class="alert alert-info">Não há postagens aguardando aprovação no momento!</div>
                  <?php endif; ?>

              </div>

              <!-- Aba Aprovados -->
              <div class="tab-pane fade" id="aprovados" role="tabpanel">
                  
                 <?php if($aprovados->num_rows > 0): ?>
                 <div class="table-responsive">
                    <table class="table table-striped table-hover mt-3">
                        <thead class="table-dark">
                            <tr>
                                <th>Data</th>
                                <th>Autor</th>
                                <th>Título</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($a = $aprovados->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($a['data_envio'])); ?></td>
                                <td><?php echo htmlspecialchars($a['autor_nome']); ?></td>
                                <td><?php echo htmlspecialchars($a['titulo']); ?></td>
                                <td>
                                    <a href="?action=reject&id=<?php echo $a['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remover do site público? Imagens serão excluídas!');"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                 </div>
                 <?php else: ?>
                    <div class="alert alert-info mt-3">A comunidade ainda não tem postagens ativas.</div>
                 <?php endif; ?>

              </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
