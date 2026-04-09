<?php
$zip = new ZipArchive;
$res = $zip->open('site_caxias_hostinger.zip');
if ($res === TRUE) {
    $zip->extractTo(__DIR__);
    $zip->close();
    echo "Extracao feita com sucesso!";
} else {
    echo "Falha na extracao do zip.";
}
// Clean up payload and this script for security
@unlink('site_caxias_hostinger.zip');
@unlink(__FILE__);
?>
