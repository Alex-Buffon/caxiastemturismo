<?php
require_once 'admin/config.php';
$pagina_titulo = "Comunidade Tem Turismo - Experiências";
$pagina_descricao = "Veja fotos, histórias e vídeos compartilhados por outros viajantes em Caxias do Sul e compartilhe a sua também!";
require_once 'includes/header.php';

// Busca postagens aprovadas
$sql = "SELECT * FROM blog_comunidade WHERE status = 'approved' ORDER BY data_envio DESC";
$posts = $conn->query($sql);
?>

<!-- Hero Section para Comunidade -->
<section class="banner-interna position-relative">
    <div class="banner-img-wrapper" style="height: 40vh; overflow:hidden; position:relative;">
        <img src="img/img.fazendasouza.png" alt="Comunidade Turística" style="width: 100%; height: 100%; object-fit: cover; filter: brightness(0.5);">
        <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-100 px-3">
            <h1 class="display-4 fw-bold" data-aos="fade-down">Comunidade Tem Turismo</h1>
            <p class="lead" data-aos="fade-up" data-aos-delay="100">Caxias do Sul vista pelos olhos de quem vive a experiência</p>
            <a href="comunidade_enviar.php" class="btn btn-primary btn-lg mt-3" data-aos="zoom-in" data-aos-delay="200">
                <i class="bi bi-camera"></i> Compartilhar Minha Viagem
            </a>
        </div>
    </div>
</section>

<!-- Feed de Histórias -->
<section class="py-5 bg-light">
    <div class="container">
        
        <?php if($posts && $posts->num_rows > 0): ?>
            <div class="row g-4" data-masonry='{"percentPosition": true }'>
                <?php while($post = $posts->fetch_assoc()): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" data-aos="fade-up">
                            
                            <!-- Imagens do Usuário -->
                            <?php 
                            $imagens = json_decode($post['imagens'], true);
                            if(!empty($imagens)): 
                                $capa = $imagens[0]; // Primeira imagem como capa
                            ?>
                                <img src="img/comunidade/<?php echo htmlspecialchars($capa); ?>" class="card-img-top" alt="Foto da viagem" style="height: 250px; object-fit: cover;" loading="lazy">
                            <?php endif; ?>

                            <div class="card-body p-4">
                                <!-- Tags e Data -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                                        <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($post['autor_nome']); ?>
                                    </span>
                                    <small class="text-muted"><i class="bi bi-calendar3"></i> <?php echo date('d/m/Y', strtotime($post['data_envio'])); ?></small>
                                </div>

                                <h4 class="card-title fw-bold mb-3"><?php echo htmlspecialchars($post['titulo']); ?></h4>
                                <p class="card-text text-secondary line-clamp-3">
                                    <?php echo nl2br(htmlspecialchars($post['conteudo'])); ?>
                                </p>
                                
                                <!-- Vídeo -->
                                <?php if(!empty($post['video_link'])): 
                                    // Parse Youtube Link
                                    $link = $post['video_link'];
                                    $video_id = '';
                                    if(strpos($link, 'youtube.com') !== false || strpos($link, 'youtu.be') !== false) {
                                        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $link, $match);
                                        $video_id = $match[1] ?? '';
                                    }
                                    if($video_id):
                                ?>
                                    <div class="ratio ratio-16x9 mt-3 rounded overflow-hidden shadow-sm">
                                        <iframe src="https://www.youtube.com/embed/<?php echo $video_id; ?>" title="YouTube video" allowfullscreen></iframe>
                                    </div>
                                <?php elseif(strpos($link, 'instagram.com') !== false): ?>
                                    <a href="<?php echo htmlspecialchars($link); ?>" target="_blank" class="btn btn-outline-danger btn-sm w-100 mt-2"><i class="bi bi-instagram"></i> Ver Vídeo no Instagram</a>
                                <?php else: ?>
                                    <a href="<?php echo htmlspecialchars($link); ?>" target="_blank" class="btn btn-outline-secondary btn-sm w-100 mt-2"><i class="bi bi-play-circle"></i> Assistir Vídeo Adicional</a>
                                <?php endif; endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <!-- Tela Vazia -->
            <div class="text-center py-5">
                <i class="bi bi-images display-1 text-muted mb-3"></i>
                <h3 class="fw-bold">Nenhuma história por enquanto</h3>
                <p class="text-muted">Seja o primeiro a compartilhar sua experiência e lindas fotos com a nossa comunidade!</p>
                <a href="comunidade_enviar.php" class="btn btn-primary">Começar agora</a>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- Adicionar script do masonry para os cards ficarem empilhados bonitos estilo Pinterest -->
<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async></script>

<?php require_once 'includes/footer.php'; ?>
