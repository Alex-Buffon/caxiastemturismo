<?php
/**
 * Header comum para todas as páginas
 * Variáveis esperadas: $page_title, $page_description, $is_subfolder (bool)
 */
$base_path = isset($is_subfolder) && $is_subfolder ? '../' : '';

// Canonical URL (evita duplicatas como /index.php)
$site_url = 'https://caxiastemturismo.com.br';
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($request_uri, PHP_URL_PATH) ?: '/';
if ($path === '/index.php' || $path === '') {
    $canonicalUrl = $site_url . '/';
} else {
    $canonicalUrl = $site_url . rtrim($path, '/');
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? ($opcoes['site_titulo'] ?? 'Caxias Tem Turismo'); ?></title>
    <meta name="description" content="<?php echo $page_description ?? ($opcoes['site_descricao'] ?? 'Descubra o melhor do turismo em Caxias do Sul.'); ?>">

    
    <?php if(!empty($opcoes['site_analytics_id'])): ?>
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $opcoes['site_analytics_id']; ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?php echo $opcoes['site_analytics_id']; ?>');
    </script>
    <?php endif; ?>

    <meta name="theme-color" content="#2E4636">
    <link rel="manifest" href="<?php echo $base_path; ?>manifest.json">
    <link rel="apple-touch-icon" href="<?php echo $base_path; ?>img/img1.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Caxias Tur">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Caxias Tem Turismo">
    
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $canonicalUrl; ?>">
    <meta property="og:title" content="<?php echo $page_title ?? 'Caxias Tem Turismo'; ?>">
    <meta property="og:description" content="<?php echo $page_description ?? 'Descubra o melhor do turismo em Caxias do Sul.'; ?>">
    <meta property="og:image" content="<?php echo isset($og_image) ? (strpos($og_image, 'http') === 0 ? $og_image : 'https://caxiastemturismo.com.br/' . ltrim($og_image, './')) : 'https://caxiastemturismo.com.br/img/img1.png'; ?>">
    <meta property="og:locale" content="pt_BR">
    <meta property="og:site_name" content="Caxias Tem Turismo">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $page_title ?? 'Caxias Tem Turismo'; ?>">
    <meta name="twitter:description" content="<?php echo $page_description ?? 'Descubra o melhor do turismo em Caxias do Sul.'; ?>">
    <meta name="twitter:image" content="<?php echo isset($og_image) ? (strpos($og_image, 'http') === 0 ? $og_image : 'https://caxiastemturismo.com.br/' . ltrim($og_image, './')) : 'https://caxiastemturismo.com.br/img/img1.png'; ?>">

    <!-- Preconnect para performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    
    <!-- Estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://unpkg.com/photoswipe@5.4.3/dist/photoswipe.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/style.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/counter.css">
    
    <!-- Canonical Tag -->
    <link rel="canonical" href="<?php echo $canonicalUrl; ?>">

    <!-- Global Site Tag (Schema.org) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "TouristInformationCenter",
      "name": "<?php echo $opcoes['site_titulo'] ?? 'Caxias Tem Turismo'; ?>",
      "description": "<?php echo $opcoes['site_descricao'] ?? 'Portal oficial de turismo de Caxias do Sul'; ?>",
      "url": "https://caxiastemturismo.com.br",
      "logo": "https://caxiastemturismo.com.br/img/img1.png",
      "image": "https://caxiastemturismo.com.br/img/img1.png",
      "telephone": "<?php echo $opcoes['contato_telefone'] ?? '(54) 98122-2284'; ?>",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?php echo $opcoes['contato_endereco'] ?? 'Caxias do Sul, RS'; ?>",
        "addressLocality": "Caxias do Sul",
        "addressRegion": "RS",
        "addressCountry": "BR"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": "-29.1678",
        "longitude": "-51.1794"
      },
      "sameAs": [
        "<?php echo $opcoes['social_facebook'] ?? ''; ?>",
        "<?php echo $opcoes['social_instagram'] ?? ''; ?>"
      ]<?php if(!empty($schema_aggregate_rating)): ?>,
      "aggregateRating": <?php echo $schema_aggregate_rating; ?>
      <?php endif; ?>
    }
    </script>

    <?php if(isset($page_title) && $_SERVER['SCRIPT_NAME'] !== '/index.php'): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Início",
        "item": "https://caxiastemturismo.com.br/"
      },{
        "@type": "ListItem",
        "position": 2,
        "name": "<?php echo $page_title; ?>",
        "item": "https://caxiastemturismo.com.br<?php echo $_SERVER['SCRIPT_NAME']; ?>"
      }]
    }
    </script>
    <?php endif; ?>

    <?php echo $extra_head ?? ''; ?>
</head>
<body>
    <div class="scroll-progress" id="scrollProgress"></div>
    <div class="toast-notification" id="toastNotification">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </svg>
        <span id="toastMessage"></span>
    </div>
