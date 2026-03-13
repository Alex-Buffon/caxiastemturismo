<?php
// Sitemap dinâmico (XML) gerado a partir do banco de dados.
// Atualize o banco para incluir novos destinos, ou revalide caches para que o sitemap seja atualizado.

require_once __DIR__ . '/admin/config.php';

header('Content-Type: application/xml; charset=UTF-8');

$baseUrl = 'https://caxiastemturismo.com.br';

function formatDate($datetime)
{
    $dt = new DateTime($datetime);
    return $dt->format('Y-m-d');
}

// URLs estáticas
$urls = [
    [
        'loc' => "$baseUrl/",
        'lastmod' => date('Y-m-d'),
        'changefreq' => 'weekly',
        'priority' => '1.0',
    ],
    [
        'loc' => "$baseUrl/servicos/agencias-de-turismo.php",
        'lastmod' => date('Y-m-d'),
        'changefreq' => 'monthly',
        'priority' => '0.8',
    ],
    [
        'loc' => "$baseUrl/servicos/agentes-de-turismo.php",
        'lastmod' => date('Y-m-d'),
        'changefreq' => 'monthly',
        'priority' => '0.8',
    ],
    [
        'loc' => "$baseUrl/servicos/transportadores-turisticos.php",
        'lastmod' => date('Y-m-d'),
        'changefreq' => 'monthly',
        'priority' => '0.8',
    ],
];

// Destinos (dinâmico)
$res = $conn->query('SELECT slug, titulo, imagem, meta_description, descricao FROM destinos ORDER BY ordem ASC');
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $loc = "$baseUrl/destinos/" . urlencode($row['slug']) . '.php';
        $image = isset($row['imagem']) && $row['imagem'] ? "$baseUrl/img/{$row['imagem']}" : null;
        $description = $row['meta_description'] ?: substr($row['descricao'], 0, 160);

        $url = [
            'loc' => $loc,
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'monthly',
            'priority' => '0.9',
        ];

        if ($image) {
            $url['image'] = [
                'loc' => $image,
                'title' => $row['titulo'] ?? '',
                'caption' => $description,
            ];
        }

        $urls[] = $url;
    }
}

// Monta XML
$xml = new XMLWriter();
$xml->openMemory();
$xml->startDocument('1.0', 'UTF-8');
$xml->startElement('urlset');
$xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
$xml->writeAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');

foreach ($urls as $url) {
    $xml->startElement('url');
    $xml->writeElement('loc', $url['loc']);
    $xml->writeElement('lastmod', $url['lastmod']);
    $xml->writeElement('changefreq', $url['changefreq']);
    $xml->writeElement('priority', $url['priority']);

    if (isset($url['image'])) {
        $xml->startElement('image:image');
        $xml->writeElement('image:loc', $url['image']['loc']);
        if (!empty($url['image']['title'])) {
            $xml->writeElement('image:title', $url['image']['title']);
        }
        if (!empty($url['image']['caption'])) {
            $xml->writeElement('image:caption', $url['image']['caption']);
        }
        $xml->endElement();
    }

    $xml->endElement();
}

$xml->endElement(); // urlset

echo $xml->outputMemory();
