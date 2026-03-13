<?php
require_once 'config.php';

$nome = 'Administrador';
$email = 'admin@caxiasturismo.com.br';
$senha_pura = 'admin';
$senha_hash = password_hash($senha_pura, PASSWORD_BCRYPT);

// Verificar se ja existe
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    // Inserir
    $stmt_insert = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt_insert->bind_param("sss", $nome, $email, $senha_hash);
    if($stmt_insert->execute()) {
        echo "Usuário Administrador criado com sucesso! Email: $email | Senha: $senha_pura\n";
    } else {
        echo "Erro ao criar: " . $conn->error;
    }
} else {
    echo "Usuário já existe. Nenhuma ação necessária.\n";
}
?>
