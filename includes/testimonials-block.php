<?php
/**
 * Exibe depoimentos aprovados de um determinado destino.
 *
 * Variáveis esperadas antes da inclusão:
 * - $conn: conexão mysqli (do config.php)
 * - $destino_nome: nome do destino (string)
 */

$destino_nome = $destino_nome ?? '';
if (empty($destino_nome)) {
    return;
}

$stmt = $conn->prepare("SELECT nome, cidade, destino, mensagem, nota, aprovado_em FROM depoimentos WHERE status = 'approved' AND destino = ? ORDER BY aprovado_em DESC LIMIT 4");
$stmt->bind_param('s', $destino_nome);
$stmt->execute();
$res_testimonials = $stmt->get_result();

if ($res_testimonials && $res_testimonials->num_rows > 0): ?>
    <section class="content-section bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto" data-aos="fade-up">
                    <h2>Depoimentos de quem já visitou</h2>
                    <p class="lead">Veja o que outras pessoas estão dizendo sobre <?php echo htmlspecialchars($destino_nome); ?>.</p>
                </div>
            </div>
            <div class="row">
                <?php while($test = $res_testimonials->fetch_assoc()): ?>
                    <div class="col-md-6 mb-4" data-aos="fade-up">
                        <div class="testimonial-card h-100">
                            <div class="stars mb-3">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi bi-star-fill" style="color: <?php echo $i <= (int)$test['nota'] ? '#FFD700' : 'rgba(0,0,0,0.2)'; ?>;"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="testimonial-text"><?php echo nl2br(htmlspecialchars($test['mensagem'])); ?></p>
                            <div class="testimonial-author">
                                <div class="testimonial-avatar"><?php echo strtoupper(substr(htmlspecialchars($test['nome']), 0, 2)); ?></div>
                                <strong><?php echo htmlspecialchars($test['nome']); ?></strong>
                                <?php if (!empty($test['cidade'])): ?>
                                    <small><?php echo htmlspecialchars($test['cidade']); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
<?php endif;
$stmt->close();
