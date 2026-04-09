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
                <div class="card-footer bg-transparent d-flex flex-column gap-2 border-top-0 pt-0 pb-3" style="position: relative; z-index: 20; pointer-events: auto;">
                    <?php if(!empty($p['url_contato'])): ?>
                        <?php 
                        $link_btn = trim($p['url_contato']);
                        $btn_text = 'Acessar Site →';
                        
                        // Extrai apenas números para testar
                        $numeros = preg_replace('/[^0-9]/', '', $link_btn);
                        
                        if (!empty($numeros) && strlen($numeros) >= 10 && strlen($numeros) <= 13 && strpos($link_btn, 'http') === false) {
                            $wa_number = $numeros;
                            
                            // Formatação amigável (ex: (54) 99999-9999)
                            if(strlen($wa_number) == 11) {
                                $wa_display = '(' . substr($wa_number, 0, 2) . ') ' . substr($wa_number, 2, 5) . '-' . substr($wa_number, 7);
                            } elseif(strlen($wa_number) == 10) {
                                $wa_display = '(' . substr($wa_number, 0, 2) . ') ' . substr($wa_number, 2, 4) . '-' . substr($wa_number, 6);
                            } else {
                                $wa_display = $wa_number;
                            }

                            if (strlen($wa_number) <= 11) $wa_number = '55' . $wa_number;
                            $link_btn = 'https://api.whatsapp.com/send?phone=' . $wa_number;
                            
                            $btn_text = '<i class="bi bi-whatsapp"></i> ' . $wa_display;
                        } elseif (strpos($link_btn, 'http') === false) {
                            $link_btn = 'https://' . $link_btn;
                        }
                        ?>
                        <a href="<?php echo htmlspecialchars($link_btn); ?>" target="_blank" rel="noopener" class="btn btn-primary btn-sm w-100">
                            <?php echo $btn_text; ?>
                        </a>
                    <?php endif; ?>
                    <?php if(!empty($p['link_mapa'])): ?>
                        <a href="<?php echo htmlspecialchars($p['link_mapa']); ?>" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm w-100">📍 Ver no Mapa</a>
                    <?php endif; ?>
                    <?php if(empty($p['link_mapa']) && empty($p['url_contato'])): ?>
                        <button class="btn btn-secondary btn-sm w-100" disabled>Mais Informações Indisponíveis</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="col-12 text-center text-muted py-5">
        <p>Nenhum prestador cadastrado nesta categoria ainda.</p>
    </div>
<?php endif; ?>
