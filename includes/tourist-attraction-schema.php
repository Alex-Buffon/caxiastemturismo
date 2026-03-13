<?php
// Schema.org JSON-LD para páginas de destino (TouristAttraction).
// Espera que $dest_data já esteja definido (dados do destino) e $page_description/ $og_image opcionais.

if (!isset($dest_data) || empty($dest_data['titulo'])) {
    return;
}

$baseUrl = 'https://caxiastemturismo.com.br';

$destUrl = $baseUrl . '/destinos/' . urlencode($dest_data['slug']) . '.php';

$imageUrl = '';
if (!empty($og_image)) {
    $imageUrl = (strpos($og_image, 'http') === 0)
        ? $og_image
        : $baseUrl . '/' . ltrim($og_image, '/');
} elseif (!empty($dest_data['imagem'])) {
    $imageUrl = $baseUrl . '/img/' . ltrim($dest_data['imagem'], '/');
}

$schema = [
    '@context' => 'https://schema.org',
    '@type' => 'TouristAttraction',
    'name' => $dest_data['titulo'],
    'description' => $page_description ?? ($dest_data['meta_description'] ?? $dest_data['descricao'] ?? ''),
    'url' => $destUrl,
];

if ($imageUrl) {
    $schema['image'] = $imageUrl;
}

$schema['address'] = [
    '@type' => 'PostalAddress',
    'addressLocality' => 'Caxias do Sul',
    'addressRegion' => 'RS',
    'addressCountry' => 'BR'
];

$schema['geo'] = [
    '@type' => 'GeoCoordinates',
    'latitude' => '-29.1678',
    'longitude' => '-51.1794'
];

$schema['sameAs'] = [
    $baseUrl
];

echo "<script type=\"application/ld+json\">" . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "</script>\n";
