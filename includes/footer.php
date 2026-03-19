<?php
if(!isset($opcoes)) {
    $base_path = isset($is_subfolder) && $is_subfolder ? '../' : '';
    require_once $base_path . 'admin/config.php';
    $opcoes = [];
    $res_opcoes = $conn->query("SELECT chave, valor FROM opcoes_site");
    while($row = $res_opcoes->fetch_assoc()) { $opcoes[$row['chave']] = $row['valor']; }
}
?>
<?php
/**
 * Footer comum para todas as páginas
 */
$base_path = isset($is_subfolder) && $is_subfolder ? '../' : '';
?>
    <footer class="footer mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h4 class="footer-title">Caxias Tem Turismo</h4>
                    <p class="mb-4">Seu guia completo para explorar o melhor do turismo rural, gastronômico e religioso na Serra Gaúcha.</p>
                    <div class="footer-social">
                        <a href="<?php echo $opcoes['social_facebook'] ?? '#'; ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
                            </svg>
                        </a>
                        <a href="<?php echo $opcoes['social_instagram'] ?? '#'; ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
                            </svg>
                        </a>
                        <a href="<?php echo "https://wa.me/" . ($opcoes['contato_whatsapp'] ?? '#'); ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-subtitle text-white">Navegação</h5>
                    <ul class="footer-links list-unstyled">
                        <li><a href="<?php echo $base_path; ?>index.php#inicio">Início</a></li>
                        <li><a href="<?php echo $base_path; ?>index.php#roteiros">Roteiros</a></li>
                        <li><a href="<?php echo $base_path; ?>index.php#destinos">Destinos</a></li>
                        <li><a href="<?php echo $base_path; ?>index.php#servicos">Serviços</a></li>
                        <li><a href="<?php echo $base_path; ?>index.php#contato">Contato</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-subtitle text-white">Destinos Populares</h5>
                    <ul class="footer-links list-unstyled">
                        <li><a href="<?php echo $base_path; ?>destinos/santa-lucia.php">Santa Lúcia</a></li>
                        <li><a href="<?php echo $base_path; ?>destinos/fazenda-souza.php">Fazenda Souza</a></li>
                        <li><a href="<?php echo $base_path; ?>destinos/terceira-legua.php">Terceira Légua</a></li>
                        <li><a href="<?php echo $base_path; ?>destinos/galopolis.php">Galópolis</a></li>
                        <li><a href="<?php echo $base_path; ?>destinos/turismo-religioso.php">Turismo Religioso</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-subtitle text-white">Contato</h5>
                    <ul class="footer-contact list-unstyled">
                        <li><?php echo $opcoes['contato_endereco'] ?? ''; ?></li>
                        <li><?php echo $opcoes['contato_telefone'] ?? ''; ?></li>
                    </ul>
                </div>
            </div>
            <hr class="footer-divider my-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; 2025 Caxias Tem Turismo. Todos os direitos reservados.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="footer-legal text-decoration-none">Política de Privacidade</a>
                    <span class="mx-2">|</span>
                    <a href="#" class="footer-legal text-decoration-none">Termos de Uso</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Botão Flutuante WhatsApp -->
    <a href="<?php echo "https://wa.me/" . ($opcoes['contato_whatsapp'] ?? '#'); ?>?text=Olá!%20Gostaria%20de%20saber%20mais%20sobre%20os%20roteiros%20turísticos%20em%20Caxias%20do%20Sul." 
       class="whatsapp-float" 
       target="_blank" 
       rel="noopener noreferrer"
       aria-label="Contato via WhatsApp">
        <svg viewBox="0 0 32 32" class="whatsapp-icon">
            <path fill="currentColor" d="M16 0c-8.837 0-16 7.163-16 16 0 2.825 0.737 5.607 2.137 8.048l-2.137 7.952 7.933-2.127c2.42 1.37 5.173 2.127 8.067 2.127 8.837 0 16-7.163 16-16s-7.163-16-16-16zM16 29.467c-2.482 0-4.908-0.646-7.07-1.87l-0.507-0.292-4.713 1.262 1.262-4.669-0.292-0.508c-1.207-2.100-1.847-4.507-1.847-6.923 0-7.435 6.048-13.483 13.483-13.483s13.483 6.048 13.483 13.483c0 7.435-6.048 13.467-13.483 13.467zM21.805 18.406c-0.366-0.183-2.164-1.067-2.499-1.189s-0.579-0.183-0.823 0.183c-0.244 0.366-0.946 1.189-1.159 1.433s-0.427 0.274-0.793 0.091c-0.366-0.183-1.545-0.57-2.941-1.815-1.087-0.97-1.821-2.168-2.034-2.534s-0.021-0.563 0.161-0.746c0.165-0.165 0.366-0.427 0.549-0.641s0.244-0.366 0.366-0.61c0.122-0.244 0.061-0.458-0.030-0.641s-0.823-1.984-1.128-2.717c-0.296-0.714-0.597-0.616-0.823-0.628-0.213-0.012-0.457-0.015-0.701-0.015s-0.641 0.091-0.976 0.458c-0.335 0.366-1.281 1.25-1.281 3.051s1.311 3.539 1.494 3.783c0.183 0.244 2.58 3.936 6.251 5.523 0.873 0.378 1.555 0.604 2.085 0.773 0.877 0.28 1.676 0.24 2.308 0.145 0.704-0.105 2.164-0.885 2.469-1.739s0.305-1.587 0.213-1.739c-0.091-0.152-0.335-0.244-0.701-0.427z"/>
        </svg>
    </a>

    <button id="backToTop" class="back-to-top" aria-label="Voltar ao topo">
        <svg viewBox="0 0 24 24" class="back-to-top-icon">
            <path fill="currentColor" d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"/>
        </svg>
    </button>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
    <script src="https://unpkg.com/photoswipe@5.4.3/dist/umd/photoswipe.umd.min.js" defer></script>
    <script src="https://unpkg.com/photoswipe@5.4.3/dist/umd/photoswipe-lightbox.umd.min.js" defer></script>
    <script src="<?php echo $base_path; ?>js/main.js" defer></script>
    <script src="<?php echo $base_path; ?>js/analytics.js" defer></script>

    <?php if (file_exists($base_path . 'js/sw.js')): ?>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?php echo $base_path; ?>js/sw.js');
            });
        }
    </script>
    <?php endif; ?>
</body>
</html>
