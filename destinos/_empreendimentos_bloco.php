<?php
/**
 * Bloco reutilizável de Empreendimentos por Destino
 * Usar: $destino_id = ID_DO_DESTINO; include '_empreendimentos_bloco.php';
 */
if (!isset($conn)) {
    require_once '../admin/config.php';
}
$res_emps = $conn->query("SELECT * FROM empreendimentos WHERE destino_id = $destino_id ORDER BY titulo ASC");
?>
<?php if($res_emps && $res_emps->num_rows > 0): ?>
    <?php while($emp = $res_emps->fetch_assoc()): ?>
    <div class="col-md-4" itemscope itemtype="http://schema.org/TouristAttraction" data-aos="zoom-in">
        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode(($emp['endereco'] ?? '') . ' Caxias do Sul RS'); ?>" target="_blank" class="card-link text-decoration-none" rel="noopener">
            <div class="card h-100 card-hover border-0" style="position: relative; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="card-img-overlay-wrapper">
                    <?php if(!empty($emp['imagem'])): ?>
                        <img src="../img/<?php echo htmlspecialchars($emp['imagem']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars(!empty($emp['alt_text']) ? $emp['alt_text'] : $emp['titulo']); ?>" loading="lazy">
                    <?php else: ?>
                        <img src="../img/img1.png" class="card-img-top" alt="<?php echo htmlspecialchars(!empty($emp['alt_text']) ? $emp['alt_text'] : $emp['titulo']); ?>" loading="lazy">
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title" itemprop="name">🏢 <?php echo htmlspecialchars($emp['titulo']); ?></h5>
                    <p class="card-text text-muted" itemprop="description"><?php echo htmlspecialchars(substr($emp['descricao'], 0, 80)) . '...'; ?></p>
                    <div class="card-features">
                        <?php if(!empty($emp['tags'])): ?>
                            <span class="feature-tag">🏷️ <?php echo htmlspecialchars($emp['tags']); ?></span>
                        <?php endif; ?>
                        <?php if(!empty($emp['contato'])): ?>
                            <span class="feature-tag" style="display: inline-flex; margin-left: 8px;">📞 <?php echo htmlspecialchars($emp['contato']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <button class="btn btn-primary btn-sm w-100">Ver Detalhes →</button>
                </div>
            </div>
        </a>
    </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="col-12 text-center text-muted py-5">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" style="opacity:.3;margin-bottom:16px" viewBox="0 0 16 16"><path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/></svg>
        <p>Nenhum empreendimento cadastrado para este destino ainda.<br><small>Adicione pelo Painel Administrativo!</small></p>
    </div>
<?php endif; ?>
