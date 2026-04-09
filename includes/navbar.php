<?php
/**
 * Navbar comum para todas as páginas
 */
$base_path = isset($is_subfolder) && $is_subfolder ? '../' : '';
$home_link = $base_path . 'index.php';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo $home_link; ?>#inicio">Caxias Tem Turismo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?php echo $home_link; ?>#inicio">Início</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $home_link; ?>#roteiros">Principais Roteiros</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="<?php echo $home_link; ?>#destinos" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Destinos</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?php echo $base_path; ?>destinos/santa-lucia.php">Santa Lúcia e Vila Oliva</a></li>
                        <li><a class="dropdown-item" href="<?php echo $base_path; ?>destinos/fazenda-souza.php">Fazenda Souza e Vila Seca</a></li>
                        <li><a class="dropdown-item" href="<?php echo $base_path; ?>destinos/terceira-legua.php">Terceira Légua</a></li>
                        <li><a class="dropdown-item" href="<?php echo $base_path; ?>destinos/galopolis.php">Galópolis e Região</a></li>
                        <li><a class="dropdown-item" href="<?php echo $base_path; ?>destinos/turismo-religioso.php">Turismo Religioso</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="<?php echo $home_link; ?>#servicos" id="navbarServicosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Prestadores de Serviço</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarServicosDropdown">
                        <li><a class="dropdown-item" href="<?php echo $base_path; ?>servicos/agencias-de-turismo.php">Agências de Turismo</a></li>
                        <li><a class="dropdown-item" href="<?php echo $base_path; ?>servicos/agentes-de-turismo.php">Agentes de Turismo</a></li>
                        <li><a class="dropdown-item" href="<?php echo $base_path; ?>servicos/transportadores-turisticos.php">Transportadores Turísticos</a></li>
                    </ul>
                <li class="nav-item"><a class="nav-link fw-bold text-primary" href="<?php echo $base_path; ?>comunidade.php"><i class="bi bi-people-fill"></i> Comunidade</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $home_link; ?>#contato">Contato</a></li>
            </ul>
        </div>
    </div>
</nav>
