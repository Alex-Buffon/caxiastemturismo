<?php
require_once 'config.php';
checkLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['csrf_token'])) {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        header("Location: prestadores.php?msg=erro");
        exit;
    }
    $id = (int)$_POST['id'];
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
}

header("Location: prestadores.php?msg=erro");
exit;
?>
