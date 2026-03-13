<?php
require_once 'config.php';
checkLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM empreendimentos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: empreendimentos.php?msg=sucesso");
exit;
?>
