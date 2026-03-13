<?php
require_once 'config.php';
checkLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Buscar se tem imagem para apagar (opcional, deixaremos a imagem na pasta por seguranca por enquanto)
    $stmt = $conn->prepare("DELETE FROM destinos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: destinos.php?msg=sucesso");
exit;
?>
