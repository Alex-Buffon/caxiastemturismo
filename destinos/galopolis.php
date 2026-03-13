<?php
require_once '../admin/config.php';
$destino_id = 4; 

// Buscar dados dinâmicos do destino para SEO
$res_dest = $conn->query("SELECT * FROM destinos WHERE id = $destino_id");
$dest_data = $res_dest->fetch_assoc();

$page_title = !empty($dest_data['meta_keywords']) ? $dest_data['titulo'] . " - " . $dest_data['meta_keywords'] : $dest_data['titulo'] . " - Caxias Tem Turismo";
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
                <li class="breadcrumb-item active" aria-current="page">Galópolis e Região</li>
            </ol>
        </div>
    </nav>

    <?php include '../includes/tourist-attraction-schema.php'; ?>

  <header class="hero" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('../img/img.galopolis.png'); height: 50vh;" data-aos="fade-in">
    <div class="container">
      <h1 style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Galópolis e Região</h1>
      <p class="lead" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">História da imigração, arquitetura europeia e natureza preservada.</p>
    </div>
  </header>

  <main class="content-section">
    <div class="container">
      <h2 class="mb-5" data-aos="fade-up">🏢 Empreendimentos em Galópolis</h2>
      <div class="row g-4">
        <?php include '_empreendimentos_bloco.php'; ?>
      </div>
    </div>
  </main>

  <?php
  // Depoimentos aprovados para este destino
  $destino_nome = $dest_data['titulo'] ?? 'Galópolis';
  include '../includes/testimonials-block.php';
  ?>

  <section id="contato" class="content-section bg-light">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 mx-auto">
          <h2 class="text-center mb-4">📬 Fale Conosco</h2>
          <div class="contact-form">
            <form>
              <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" class="form-control" id="name" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" required>
              </div>
              <div class="mb-3">
                <label for="message" class="form-label">Mensagem</label>
                <textarea class="form-control" id="message" rows="5" required></textarea>
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>
