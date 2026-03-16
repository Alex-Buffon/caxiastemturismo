<?php
require_once 'config.php';
checkLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['csrf_token'])) {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        header("Location: destinos.php?msg=erro");
        exit;
    }
    $id = (int)$_POST['id'];
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM destinos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    header("Location: destinos.php?msg=sucesso");
    exit;
}

// Se não for POST válido, redireciona
header("Location: destinos.php?msg=erro");
exit;
?>
