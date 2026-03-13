<?php
if (!isset($conn)) {
    require_once '../admin/config.php';
}

$tipo_servico = $tipo_servico ?? 'agencia';
$sql = "SELECT * FROM prestadores WHERE tipo = '$tipo_servico' ORDER BY nome ASC";
$res = $conn->query($sql);
?>

<?php if($res && $res->num_rows > 0): ?>
    <?php while($p = $res->fetch_assoc()): ?>
        <div class="col-md-4" itemscope itemtype="http://schema.org/LocalBusiness" data-aos="zoom-in">
            <a href="<?php echo !empty($p['link_mapa']) ? htmlspecialchars($p['link_mapa']) : '#'; ?>" target="_blank" class="card-link text-decoration-none" rel="noopener">
                <div class="card h-100 card-hover border-0" style="position: relative; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div class="card-img-overlay-wrapper">
                        <?php if(!empty($p['imagem'])): ?>
                            <img src="../img/<?php echo htmlspecialchars($p['imagem']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($p['nome']); ?>" loading="lazy">
                        <?php else: ?>
                            <img src="../img/img1.png" class="card-img-top" alt="<?php echo htmlspecialchars($p['nome']); ?>" loading="lazy">
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title" itemprop="name">🏪 <?php echo htmlspecialchars($p['nome']); ?></h5>
                        <p class="card-text text-muted" itemprop="description"><?php echo htmlspecialchars(substr($p['descricao'], 0, 80)) . '...'; ?></p>
                        <div class="card-features">
                            <?php if(!empty($p['contato'])): ?>
                                <span class="feature-tag">📞 <?php echo htmlspecialchars($p['contato']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <button class="btn btn-primary btn-sm w-100">Mais Informações →</button>
                    </div>
                </div>
            </a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="col-12 text-center text-muted py-5">
        <p>Nenhum prestador cadastrado nesta categoria ainda.</p>
    </div>
<?php endif; ?>
