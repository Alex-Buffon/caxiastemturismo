<?php
// Criar usuário admin
require_once 'admin/config.php';

// Verificar se já existe
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$email = 'admin@caxiasturismo.com.br';
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    // Hash da senha 'admin'
    $senha_hash = '$2y$10$rrmN7IqzpLvLSPxMNfWu3OCVQW3NOFVGPY0FWYl7jCmkqfVSHnAvy';
    $nome = 'Administrador';
    
    $stmt_insert = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt_insert->bind_param("sss", $nome, $email, $senha_hash);
    
    if($stmt_insert->execute()) {
        echo "✓ Usuário admin criado com sucesso!\n";
        echo "Email: admin@caxiasturismo.com.br\n";
        echo "Senha: admin\n";
    } else {
        echo "✗ Erro ao criar usuário: " . $conn->error . "\n";
    }
} else {
    echo "✓ Usuário já existe!\n";
}

$conn->close();
?>
