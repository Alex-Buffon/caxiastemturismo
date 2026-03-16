<?php
require_once '../admin/config.php';
$destino_id = 1; 

// Buscar dados dinâmicos do destino para SEO
$stmt = $conn->prepare("SELECT * FROM destinos WHERE id = ?");
$stmt->bind_param("i", $destino_id);
$stmt->execute();
$res_dest = $stmt->get_result();
$dest_data = $res_dest->fetch_assoc();

$page_title = $dest_data['titulo'] . " - Caxias Tem Turismo";
$page_description = !empty($dest_data['meta_description']) ? $dest_data['meta_description'] : $dest_data['descricao'];
$og_image = "img/" . $dest_data['imagem'];
$is_subfolder = true;

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb" data-aos="fade-right">
                <li class="breadcrumb-item"><a href="../index.php">Início</a></li>
                <li class="breadcrumb-item"><a href="../index.php#destinos">Destinos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Santa Lúcia e Vila Oliva</li>
            </ol>
        </div>
    </nav>

    <?php include '../includes/tourist-attraction-schema.php'; ?>

  <header class="hero" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('../img/img.santalucia.png'); height: 50vh;" data-aos="fade-in">
    <div class="container">
      <h1 style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Turismo Rural em Santa Lúcia e Vila Oliva</h1>
      <p class="lead" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Belezas naturais, cultura e tranquilidade na Serra Gaúcha.</p>
    </div>
  </header>

  <main class="content-section">
    <div class="container">
      <h2 class="mb-5" data-aos="fade-up">🏢 Empreendimentos em Santa Lúcia</h2>
      <!-- Empreendimentos dinâmicos via banco de dados -->
      <div class="row g-4">
        <?php include '_empreendimentos_bloco.php'; ?>
      </div>
    </div>
  </main>

  <?php
  // Depoimentos aprovados para este destino
  $destino_nome = $dest_data['titulo'] ?? 'Santa Lúcia';
  include '../includes/testimonials-block.php';
  ?>



<?php require_once '../includes/footer.php'; ?>
</body>
</html>
