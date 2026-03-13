<?php
require_once __DIR__ . '/admin/config.php';

// Criar tabela se não existir (segurança redundante)
$conn->query("CREATE TABLE IF NOT EXISTS depoimentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  cidade VARCHAR(255) DEFAULT NULL,
  destino VARCHAR(255) DEFAULT NULL,
  mensagem TEXT NOT NULL,
  nota TINYINT(1) DEFAULT NULL,
  status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  aprovado_em TIMESTAMP NULL DEFAULT NULL,
  ip VARCHAR(45) DEFAULT NULL,
  user_agent TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

// Adicionar colunas caso estejam faltando (compatibilidade com versões antigas)
$conn->query("ALTER TABLE depoimentos ADD COLUMN IF NOT EXISTS cidade VARCHAR(255) DEFAULT NULL;");
$conn->query("ALTER TABLE depoimentos ADD COLUMN IF NOT EXISTS destino VARCHAR(255) DEFAULT NULL;");
$conn->query("ALTER TABLE depoimentos ADD COLUMN IF NOT EXISTS ip VARCHAR(45) DEFAULT NULL;");
$conn->query("ALTER TABLE depoimentos ADD COLUMN IF NOT EXISTS user_agent TEXT DEFAULT NULL;");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$destino = trim($_POST['destino'] ?? '');
$mensagem = trim($_POST['mensagem'] ?? '');
$nota = (int)($_POST['nota'] ?? 0);

if ($nome === '' || $email === '' || $mensagem === '' || $nota < 1 || $nota > 5) {
    header('Location: index.php?testimonial_status=error&msg=Preencha+todos+os+campos+e+avalie+com+1+a+5+estrelas');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: index.php?testimonial_status=error&msg=Email+inv%C3%A1lido');
    exit;
}

$ip = $_SERVER['REMOTE_ADDR'] ?? null;
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;

$stmt = $conn->prepare('INSERT INTO depoimentos (nome, email, cidade, destino, mensagem, nota, ip, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->bind_param('sssssiis', $nome, $email, $cidade, $destino, $mensagem, $nota, $ip, $user_agent);
$stmt->execute();
$stmt->close();

header('Location: index.php?testimonial_status=success');
exit;
