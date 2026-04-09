<?php
require_once 'admin/config.php';
$pagina_titulo = "Comunidade Tem Turismo - Enviar Relato";
$pagina_descricao = "Compartilhe a sua viagem com a nossa comunidade";
require_once 'includes/header.php';

$msg = '';
$tipo_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enviar'])) {
    // Sanitização e validação
    $nome = $conn->real_escape_string(trim($_POST['nome']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $titulo = $conn->real_escape_string(trim($_POST['titulo']));
    $conteudo = $conn->real_escape_string(trim($_POST['conteudo']));
    $video_link = $conn->real_escape_string(trim($_POST['video_link']));
    $ip = $_SERVER['REMOTE_ADDR'];
    $imagens_finais = [];

    // Pasta de upload
    $upload_dir = 'img/comunidade/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Processamento de Imagens
    if (isset($_FILES['imagens']) && !empty($_FILES['imagens']['name'][0])) {
        $total_files = count($_FILES['imagens']['name']);
        
        // Limita a máximo de 3 imagens
        if($total_files > 3) $total_files = 3; 

        for($i = 0; $i < $total_files; $i++) {
            $tmp_name = $_FILES['imagens']['tmp_name'][$i];
            $name = basename($_FILES['imagens']['name'][$i]);
            $size = $_FILES['imagens']['size'][$i];
            
            if($size > 0 && $size < 5000000) { // Max 5MB per image
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $nome_unico = uniqid('comunidade_') . '.' . $ext;
                    if(move_uploaded_file($tmp_name, $upload_dir . $nome_unico)) {
                        $imagens_finais[] = $nome_unico;
                    }
                }
            }
        }
    }

    $json_imagens = json_encode($imagens_finais);

    $sql = "INSERT INTO blog_comunidade (autor_nome, autor_email, titulo, conteudo, video_link, imagens, ip_autor) 
            VALUES ('$nome', '$email', '$titulo', '$conteudo', '$video_link', '$json_imagens', '$ip')";
    
    if ($conn->query($sql) === TRUE) {
        $msg = "Seu relato e fotos foram enviados com sucesso! Ele passará por uma rápida revisão da nossa equipe (para evitar spam) e logo estará no ar.";
        $tipo_msg = "success";
    } else {
        $msg = "Ocorreu um erro so enviar. Tente novamente mais tarde.";
        $tipo_msg = "danger";
    }
}
?>

<section class="py-5 bg-light min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="text-center mb-4">
                    <h1 class="fw-bold" data-aos="fade-down">Compartilhe sua Experiência</h1>
                    <p class="text-muted" data-aos="fade-up">Mostre para outros viajantes os lugares incríveis que você conheceu em Caxias do Sul.</p>
                </div>

                <?php if($msg): ?>
                    <div class="alert alert-<?php echo $tipo_msg; ?> text-center shadow-sm" role="alert">
                        <i class="bi <?php echo $tipo_msg == 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'; ?> me-2"></i> <?php echo $msg; ?>
                        <?php if($tipo_msg == 'success'): ?>
                            <div class="mt-3">
                                <a href="comunidade.php" class="btn btn-primary btn-sm">Voltar à Comunidade</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; if($tipo_msg != 'success'): ?>

                <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body p-4 p-md-5">
                        <form action="" method="POST" enctype="multipart/form-data">
                            
                            <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-person"></i> Seus Dados</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label form-label-sm fw-bold">Seu Nome *</label>
                                    <input type="text" name="nome" class="form-control" required placeholder="Como quer ser chamado?">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label form-label-sm fw-bold">Seu E-mail *</label>
                                    <input type="email" name="email" class="form-control" required placeholder="Nunca será exibido">
                                    <div class="form-text">Usado apenas para contato interno, caso necessário.</div>
                                </div>
                            </div>

                            <hr class="text-muted opacity-25">

                            <h5 class="fw-bold mb-3 text-primary mt-4"><i class="bi bi-pencil"></i> Sobre sua Viagem</h5>
                            <div class="mb-3">
                                <label class="form-label form-label-sm fw-bold">Título da Postagem *</label>
                                <input type="text" name="titulo" class="form-control" required placeholder="Ex: Roteiro inesquecível pelo interior">
                            </div>
                            <div class="mb-4">
                                <label class="form-label form-label-sm fw-bold">Conteúdo *</label>
                                <textarea name="conteudo" class="form-control" rows="6" required placeholder="Escreva sobre o que você viu, recomendações de pratos, dicas ocultas..."></textarea>
                            </div>

                            <hr class="text-muted opacity-25">

                            <h5 class="fw-bold mb-3 text-primary mt-4"><i class="bi bi-images"></i> Mídias</h5>
                            
                            <div class="mb-3">
                                <label class="form-label form-label-sm fw-bold">Link de um Vídeo (Opcional)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-youtube text-danger"></i></span>
                                    <input type="url" name="video_link" class="form-control" placeholder="Cole o link do YouTube, Instagram, etc">
                                </div>
                                <div class="form-text">Dica: No YouTube, o seu vídeo aparecerá abertamente dentro do site!</div>
                            </div>

                            <div class="mb-4 p-4 border rounded bg-light text-center">
                                <i class="bi bi-cloud-arrow-up display-4 text-secondary mb-3"></i>
                                <label class="d-block form-label fw-bold mb-1">Fotos da Viagem (Até 3 imagens)</label>
                                <p class="small text-muted mb-3">Formatos aceitos: JPG, PNG, WEBP. Máx 5MB por foto.</p>
                                <input type="file" name="imagens[]" class="form-control" accept=".jpg,.jpeg,.png,.webp" multiple>
                            </div>

                            <div class="d-grid mt-5">
                                <button type="submit" name="enviar" class="btn btn-primary btn-lg rounded-pill fw-bold">
                                    <i class="bi bi-send"></i> Enviar Postagem
                                </button>
                                <a href="comunidade.php" class="btn btn-link text-muted mt-2 text-decoration-none">← Cancelar</a>
                            </div>

                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
