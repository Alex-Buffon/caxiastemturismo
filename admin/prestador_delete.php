<?php
require_once 'config.php';
checkLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Buscar imagem para deletar do disco (opcional, mas boa prática)
    $stmt_img = $conn->prepare("SELECT imagem FROM prestadores WHERE id = ?");
    $stmt_img->bind_param("i", $id);
    $stmt_img->execute();
    $res_img = $stmt_img->get_result();
    if ($res_img->num_rows > 0) {
        $img = $res_img->fetch_assoc()['imagem'];
        if (!empty($img) && file_exists('../img/' . $img)) {
            unlink('../img/' . $img);
        }
    }

    $stmt = $conn->prepare("DELETE FROM prestadores WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: prestadores.php?msg=sucesso");
exit;
?>
