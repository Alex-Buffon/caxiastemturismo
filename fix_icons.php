<?php
$file = 'e:/caxiastemturismo/index.php';
$content = file_get_contents($file);

// Gastronomia - Trocar ícone de "terminal" por "utensils"
$old_gastronomia = 'M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm5.5 10a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1H6a.5.5 0 0 0-.5.5M5 5.5A1.5 1.5 0 0 0 6.5 7h3A1.5 1.5 0 0 0 11 5.5v-1A1.5 1.5 0 0 0 9.5 3h-3A1.5 1.5 0 0 0 5 4.5zM6.5 6a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5z';
$new_gastronomia = 'M8 1a1 1 0 0 1 1 1v13.5a.5.5 0 0 1-1 0V2a1 1 0 0 1 1-1M5.889 6.889c1.556-1.556 4.667-1.556 6.222 0l.354.354a.5.5 0 0 1-.708.708l-.354-.354c-1.167-1.167-3.5-1.167-4.667 0l-.354.354a.5.5 0 1 1-.708-.708l.354-.354Z';
$content = str_replace($old_gastronomia, $new_gastronomia, $content);

// Natureza - Trocar ícone de "bars" por "tree"
$old_natureza = 'M4 11H2v3h2zm5-4H7v7h2zm5-5v12h-2V2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm-5 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1z';
$new_natureza = 'M8.416.223a.5.5 0 0 0-.832 0l-3 4.5A.5.5 0 0 0 5 5.5h.098L3.076 8.735A.5.5 0 0 0 3.5 9.5h.191l-1.638 3.276a.5.5 0 0 0 .447.724H7V15h2v-1.5h4.5a.5.5 0 0 0 .447-.724L12.31 9.5h.191a.5.5 0 0 0 .424-.765L10.902 5.5H11a.5.5 0 0 0 .416-.777l-3-4.5z';
$content = str_replace($old_natureza, $new_natureza, $content);

// Cultura - Trocar ícone de "terminal prompt" por "heritage/people"
$old_cultura = 'M6 9a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3A.5.5 0 0 1 6 9M3.854 4.146a.5.5 0 1 0-.708.708L4.793 6.5 3.146 8.146a.5.5 0 1 0 .708.708l2-2a.5.5 0 0 0 0-.708z';
$new_cultura = 'M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.994-1.451A1 1 0 0 1 5 5h3a1 1 0 0 1 0 2H5a1 1 0 0 1-.994-.549ZM5 12a4 4 0 0 0-4 4h14a4 4 0 0 0-4-4H5zm0-1h10a5 5 0 0 1 5 5v1H0v-1a5 5 0 0 1 5-5z';
$content = str_replace($old_cultura, $new_cultura, $content);

file_put_contents($file, $content);
echo "Ícones atualizados com sucesso!\n";
?>
