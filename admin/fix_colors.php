<?php
$admin_dir = 'e:/caxiastemturismo/admin/';
$files = scandir($admin_dir);

$target_color = '#2E4636';
$orange_color = '#2E4636';

foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;
    
    $path = $admin_dir . $file;
    $content = file_get_contents($path);
    
    $changed = false;
    
    // Substituir botões laranja (#2E4636) pelo verde institucional
    if (strpos($content, $orange_color) !== false) {
        $content = str_replace($orange_color, $target_color, $content);
        $changed = true;
    }
    
    // Substituir btn-primary do bootstrap por uma cor customizada se necessário 
    // ou apenas garantir que os botões de "Salvar" e "Novo" usem o verde.
    // Muitos arquivos já usam inline style para o verde no botão salvar, vamo garantir consistência.
    
    if ($changed) {
        file_put_contents($path, $content);
        echo "Cores padronizadas em $file\n";
    }
}
?>
